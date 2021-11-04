<?php

namespace App\Services;

use App\Entity\Tournament;
use App\Entity\Participant;
use App\Exception\Tournament\TournamentNotFoundException;
use App\Repository\TournamentRepository;

class TournamentService
{
    public function __construct(
        private TournamentRepository $tournamentRepository
    ) {
    }

    public function getTournament(string $id): ?Tournament
    {
        $tournament =  $this->tournamentRepository->find($id);

        if (!$tournament) {
            throw new TournamentNotFoundException();
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
