<?php

namespace App\Model;

class Participant
{
    public function __construct(
        public string $id,
        public string $name,
        public int $elo = 0
    ) {
    }
}
