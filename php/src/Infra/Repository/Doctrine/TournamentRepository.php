<?php

namespace App\Infra\Repository\Doctrine;

use App\Domain\Model\Tournament\Exception\TournamentNotFoundException;
use App\Domain\Model\Tournament\Tournament;
use App\Domain\Port\Output\TournamentStorageInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tournament|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournament|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournament[]    findAll()
 * @method Tournament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentRepository extends ServiceEntityRepository implements TournamentStorageInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournament::class);
    }

    public function getById(string $id): ?Tournament
    {
        $tournament = $this->find($id);
        if (!$tournament) throw new TournamentNotFoundException();
        return $tournament;
    }

    public function create(Tournament $tournament): Tournament
    {
        $this->_em->persist($tournament);
        $this->_em->flush();
        return $tournament;
    }
}
