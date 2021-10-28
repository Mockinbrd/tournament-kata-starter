<?php

namespace App\Model;

class Participant
{
    public string $id;
    public string $name;
    public int $elo;

    public function __construct($id, $name, $elo) {
        $this->id = $id;
        $this->name = $name;
        $this->elo = $elo;
    }
}