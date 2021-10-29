<?php

namespace App\Services;

use App\Entity\Tournament;
use App\Entity\Participant;
use App\Repository\TournamentRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TournamentService
{
    public function __construct(
        private TournamentRepository $tournamentRepository
    ) {
    }

    public function getTournament(string $id): Tournament
    {
        $tournament = $this->tournamentRepository->find($id);

        if (null === $tournament) {
            throw new NotFoundHttpException("Le tournoi n'existe pas");
        }

        return $tournament;
    }

    public function createTournament(array $parameters): Tournament
    {
        return $this->tournamentRepository->create($parameters);
    }

    public function updateTournament(Tournament $tournament, array $parameters): Tournament
    {
        return $this->tournamentRepository->update($tournament, $parameters);
    }

    public function addParticipant(Tournament $tournament, Participant $participant): Tournament
    {
        return $this->tournamentRepository->addParticipant($tournament, $participant);
    }
}
