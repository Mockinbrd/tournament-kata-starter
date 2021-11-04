<?php

namespace App\Services;

use App\Entity\Tournament;
use App\Entity\Participant;
use App\Exception\Participant\ParticipantNotFoundException;
use App\Repository\ParticipantRepository;

class ParticipantService
{
    public function __construct(
        private ParticipantRepository $participantRepository,
        private TournamentService $tournamentService
    ) {
    }

    public function createParticipant(array $parameters): Participant
    {
        return $this->participantRepository->create($parameters);
    }

    public function getParticipant(Tournament $tournament, string $participantId): Participant
    {
        $participant = $this->participantRepository->findOneByTournament($tournament, $participantId);

        if (!$participant) {
            throw new ParticipantNotFoundException();
        }

        return $participant;
    }
}
