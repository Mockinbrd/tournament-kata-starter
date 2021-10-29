<?php

namespace App\Services;


use App\Entity\Tournament;
use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use App\Repository\TournamentRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ParticipantService
{
    public function __construct(
        private ParticipantRepository $participantRepository
    ) {
    }

    public function createParticipant(array $parameters): Participant
    {
        return $this->participantRepository->create($parameters);
    }

    public function getParticipant(Tournament $tournament, string $participantId): Participant
    {
        $participant = $this->participantRepository->findOneByTournament($tournament, $participantId);

        if (null === $participant) {
            throw new NotFoundHttpException("Le participant n'existe pas");
        }

        return $participant;
    }
}
