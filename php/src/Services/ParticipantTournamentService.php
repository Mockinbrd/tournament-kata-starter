<?php
namespace App\Services;

use App\Model\Tournament;
use App\Model\Participant;
use App\Services\ParticipantService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ParticipantTournamentService {

    public function __construct(
        private ParticipantService $participantService,
        private TournamentService $tournamentService,
        private SessionInterface $session
    ) {
    }

    public function saveParticipantOnTournament(Participant $participant, Tournament $tournament) {

        $tournament->addParticipant($participant);

        $this->session->set($tournament->id, $tournament);
        $this->session->save();
    }
}

