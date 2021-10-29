<?php

namespace App\Tests;

use Metadata\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class WebTestCaseWithDatabase extends ApiTestCase
{
    protected Client $client;
    protected EntityManager $em;
    protected SchemaTool $schemaTool;
    /**
     * @var ClassMetadata[]
     */
    protected array $metaData;


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

        // Get the entity manager from the service container
        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $this->em->getConnection()->beginTransaction();

        // Run the schema update tool using our entity metadata
        $this->metaData = $this->em->getMetadataFactory()->getAllMetadata();
        $this->schemaTool = new SchemaTool($this->em);
        $this->schemaTool->updateSchema($this->metaData);
    }

    // Trunkate the whole database on tearDown
    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Run command to delete all data from the database
        $this->em->getConnection()->rollback();

        $this->em->close();
        /* $this->em = null; // avoid memory leaks */
    }
}