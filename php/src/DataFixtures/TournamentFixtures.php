<?php

namespace App\DataFixtures;

use App\Entity\Tournament;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TournamentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tournament = new Tournament();
        $tournament->setName('Rolland Garros');
        $manager->persist($tournament);

        $manager->flush();
    }
}
