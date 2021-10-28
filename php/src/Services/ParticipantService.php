<?php

namespace App\Services;

use App\Model\Tournament;
use App\Model\Participant;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ParticipantService
{
    public function __construct(
        private RequestStack $requestStack
    ) {
        $this->session = $requestStack->getSession();
    }

    public function getParticipant(string $id): ?Tournament
    {
        return $this->session->get($id);
    }
}
