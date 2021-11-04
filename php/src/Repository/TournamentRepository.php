<?php

namespace App\Repository;

use App\Entity\Participant;
use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tournament|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournament|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournament[]    findAll()
 * @method Tournament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournament::class);
    }

    public function create(array $parameters): Tournament
    {
        $tournament = new Tournament();
        $tournament->setName($parameters['name']);


        $this->_em->persist($tournament);
        $this->_em->flush();
        return $tournament;
    }

    public function update(Tournament $tournament, array $parameters): Tournament
    {
        foreach ($parameters as $key => $param) {
            $method = 'set' . ucfirst($key);

            if (method_exists($tournament, $method)) {
                $tournament->$method($param);
            }
        }

        $this->_em->flush();

        return $tournament;
    }

    public function addParticipant(Tournament $tournament, Participant $participant): Tournament
    {
        $tournament->addParticipant($participant);
        $this->_em->flush();

        return $tournament;
    }

    public function removeParticipant(Tournament $tournament, Participant $participant): void
    {
        $tournament->removeParticipant($participant);

        $this->_em->flush();
    }
}
