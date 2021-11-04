<?php

namespace App\Domain\Model\Tournament;

use App\Domain\Model\Tournament\Tournament;

class TournamentBuilder
{
    public function partialTournamentToCreate(array $parameters)
    {
        $tournament = new Tournament();
        foreach ($parameters as $key => $param) {
            $method = 'set' . ucfirst($key);

            if (method_exists($tournament, $method)) {
                $tournament->$method($param);
            }
        }
        return $tournament;
    }
}
