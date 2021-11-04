<?php

namespace App\Tests;

use Metadata\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\DataFixtures\Loader;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Tournament;

class WebTestCaseWithDatabase extends ApiTestCase
{
    protected Client $client;
    protected EntityManager $em;
    protected SchemaTool $schemaTool;

    protected function setUp(): void
    {
        parent::setUp();

        // This is tricky. You need to boot the kernel to create the DDBB.                   
        // But if you boot it with static::bootKernel(),                        
        // an error will be thrown if you create the client in your tests, 
        // because static::createClient() tries boot again the kernel.
        // That's why I create the client and boot the kernel only once here.
        $this->client = static::createClient();

        // Make sure we are in the test environment
        if ('test' !== self::$kernel->getEnvironment()) {
            throw new \LogicException('Tests cases with fresh database must be executed in the test environment');
        }

        $this->em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // Get the entity manager from the service container
        $this->metaData = $this->em->getMetadataFactory()->getAllMetadata();
        $this->schemaTool = new SchemaTool($this->em);
        $this->schemaTool->updateSchema($this->metaData);
    }

    // Run the schema update tool using our entity metadata
    private function initDatabase(): void
    {
        $metaData = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->updateSchema($metaData);

        // dd($entityManager->getRepository(Tournament::class)->findAll());
    }
    // Helper function to add fixtures
    public function addFixture($className)
    {
        $loader = new Loader();
        $loader->addFixture(new $className);

        $purger = new ORMPurger($this->em);
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }

    // Trunkate the whole database on tearDown
    protected function tearDown(): void
    {
        parent::tearDown();
        // Purge all the fixtures data when the tests are finished
        $purger = new ORMPurger($this->em);
        // Purger mode 2 truncates, resetting autoincrements
        $purger->setPurgeMode(2);
        $purger->purge();

        /* // Run command to delete all data from the database
        $this->em->getConnection()->rollback();

        $this->em->close(); */
    }
}
