<?php

namespace App\Domain\Model\Tournament\Exception;

use Exception;

class TournamentNotFoundException extends Exception
{
    protected $message = "Le tournoi n'existe pas";
}
