<?php

namespace App\Services;

use App\Entity\Tournament;
use App\Entity\Participant;
use App\Repository\TournamentRepository;
use App\Services\ParticipantService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ParticipantTournamentService
{
    public function __construct(
        private ParticipantService $participantService,
        private TournamentService $tournamentService,
        private TournamentRepository $tournamentRepository
    ) 
    {}

    public function addParticipantOnTournament(Tournament $tournament, array $params): Participant
    {
        $participant = $this->participantService->createParticipant($params);

        $this->tournamentService->addParticipant($tournament, $participant);

        return $participant;
    }

    public function deleteParticipantFromTournament(Tournament $tournament, Participant $participant): void
    {
        $this->tournamentRepository->removeParticipant($tournament, $participant);
    }
}
