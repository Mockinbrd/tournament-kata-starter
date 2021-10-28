<?php

namespace App\Services;

use App\Model\Tournament;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TournamentService
{
    public function __construct(
        private RequestStack $requestStack
    ) {
        $this->session = $requestStack->getSession();
    }

    public function getTournament(string $id): ?Tournament
    {
        return $this->session->get($id);
    }

    public function saveTournament(Tournament $tournament)
    {
        $this->session->set($tournament->id, $tournament);
        $this->session->save();
    }
}
