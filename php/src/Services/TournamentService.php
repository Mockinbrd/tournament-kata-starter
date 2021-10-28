<?php

namespace App\Services;

use App\Model\Tournament;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TournamentService
{
    public function __construct(
        private RequestStack $requestStack
    ) {
        $this->session = $requestStack->getSession();
    }

    public function getTournament(string $id): ?Tournament
    {
        $tournament = $this->session->get($id);

        if (null === $tournament) {
            throw new NotFoundHttpException("Le tournoi n'existe pas");
        }
        
        return $tournament;
    }

    public function saveTournament(Tournament $tournament)
    {
        $this->session->set($tournament->id, $tournament);
        $this->session->save();
    }
}
