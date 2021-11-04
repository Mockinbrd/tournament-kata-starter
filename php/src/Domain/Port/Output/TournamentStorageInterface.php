<?php

namespace App\Domain\Port\Output;

use App\Domain\Model\Tournament\Tournament;

interface TournamentStorageInterface
{
    public function getById(string $tournamentId): ?Tournament;
    public function create(Tournament $tournament): Tournament;
}
