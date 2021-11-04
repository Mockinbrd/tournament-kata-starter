<?php

namespace App\Repository;

use App\Entity\Tournament;
use App\Entity\Participant;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    public function create(array $parameters): Participant
    {
        $participant = new Participant();
        $participant->setName($parameters['name']);
        $participant->setElo($parameters['elo']);

        $this->_em->persist($participant);
        $this->_em->flush();

        return $participant;
    }

    public function findOneByTournament(Tournament $tournament, string $participantId): ?Participant
    {
        return $this
            ->createQueryBuilder('p')
            ->join('p.tournaments', 't')
            ->andWhere('t.id = :tournamentId')
            ->andWhere('p.id = :participantId')
            ->setParameter('tournamentId', $tournament->getId(), 'ulid')
            ->setParameter('participantId', $participantId, 'ulid')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
