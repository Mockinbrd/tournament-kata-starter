<?php

namespace App\Exception\Tournament;

use Exception;

class TournamentNotFoundException extends Exception
{
    protected $message = "Le tournoi n'existe pas";
}
