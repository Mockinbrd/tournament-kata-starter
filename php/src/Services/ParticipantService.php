<?php
namespace App\Services;

use App\Model\Tournament;
use App\Model\Participant;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ParticipantService {
    private SessionInterface $session;

    public function __construct(SessionInterface $session) {
        $this->session = $session;
    }

    public function getParticipant(string $id) : ?Tournament {
        return $this->session->get($id);
    }
}