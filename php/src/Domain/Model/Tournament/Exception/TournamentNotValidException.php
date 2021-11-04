<?php

namespace App\Domain\Model\Tournament\Exception;

use Exception;

class TournamentNotValidException extends Exception
{
    protected $message = "Les données de tournoi fournies ne sont pas valides";
}
