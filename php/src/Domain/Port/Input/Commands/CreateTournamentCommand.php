<?php

namespace App\Domain\Port\Input\Commands;

use App\Domain\Model\Tournament\Tournament;
use App\Domain\Port\Output\TournamentStorageInterface;

class CreateTournamentCommand
{
    public function __construct(
        private TournamentStorageInterface $tournamentStorage
    ) {
    }

    public function execute(Tournament $tournamentOnCreation): Tournament
    {
        return $this->tournamentStorage->create($tournamentOnCreation);
    }
}
