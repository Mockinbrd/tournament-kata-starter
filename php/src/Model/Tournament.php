<?php

namespace App\Model;

class Tournament
{
    public string $id;
    public string $name;
    public array $participants;

    public function __construct($id, $name, $participants = []) {
        $this->id = $id;
        $this->name = $name;
        $this->participants = $participants;
    }

    public function addParticipant(Participant $participant): self
    {
        $this->participants[] = $participant;

        return $this;
    }

    public function getParticipants(): array
    {
        return $this->participants;
    }
}