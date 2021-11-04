<?php

namespace App\Domain\Model\Participant\Exception;

use Exception;

class ParticipantNotFoundException extends Exception
{
    protected $message = "Le participant n'existe pas";
}
