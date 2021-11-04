<?php

namespace App\Exception\Participant;

use Exception;

class ParticipantNotFoundException extends Exception
{
    protected $message = "Le participant n'existe pas";
}
