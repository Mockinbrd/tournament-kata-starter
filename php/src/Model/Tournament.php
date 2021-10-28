<?php

namespace App\Model;

class Tournament
{
    public function __construct(
        public string $id,
        public string $name,
        public array $participants = []
    ) {
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

    public function setParticipants(array $participantsToSet): self
    {
        $this->participants = $participantsToSet;

        return $this;
    }
}
