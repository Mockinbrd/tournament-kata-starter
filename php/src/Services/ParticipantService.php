<?php

namespace App\Services;

use App\Model\Tournament;
use App\Model\Participant;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ParticipantService
{
    public function __construct(
        private RequestStack $requestStack
    ) {
        $this->session = $requestStack->getSession();
    }

    public function getParticipant(Tournament $tournament, string $participantId): ?Participant
    {
        $tournamentParticipants = $tournament->getParticipants();
        
        foreach($tournamentParticipants as $participant)
        {
            if($participant->id === $participantId)
            {
                return $participant;
            }
        }

        throw new NotFoundHttpException("Le participant n'existe pas");
    }

    public function deleteParticipant(Tournament $tournament, string $participantId): void
    {
        $tournamentParticipants = $tournament->getParticipants();

        foreach($tournamentParticipants as $key => $participant)
        {
            if($participant->id === $participantId)
            {
                unset($tournamentParticipants[$key]);
            }
        }

        $tournament->setParticipants($tournamentParticipants);

        $this->session->set($tournament->id, $tournament);
        $this->session->save();
    }
}
