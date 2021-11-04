<?php

namespace App\Domain\Port\Input\Queries;

use App\Domain\Model\Tournament\Exception\TournamentNotFoundException;
use App\Domain\Model\Tournament\Tournament;
use App\Domain\Port\Output\TournamentStorageInterface;

class GetTournamentQuery
{
    public function __construct(
        private TournamentStorageInterface $tournamentStorage
    ) {
    }

    public function execute(string $tournamentId): ?Tournament
    {
        $tournament = $this->tournamentStorage->getById($tournamentId);

        if (!$tournament) {
            throw new TournamentNotFoundException();
        }

        return $tournament;
    }
}
