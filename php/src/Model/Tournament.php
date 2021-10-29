<?php

namespace App\Model;

class Tournament
{
    public const TYPE_SINGLE_ELIMINATION = "SingleBracketElimination";
    public const TYPES = [self::TYPE_SINGLE_ELIMINATION];

    public function __construct(
        public string $id,
        public string $name,
        public ?string $type = null, // A QUAND LES ENUMS ICI HEIN... PHP 8.1 ASAP OR I WILL GO HARD
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

    public function getType():string 
    {
        return $this->type;
    }

    public function setType(string $type):self
    {
        $this->type = $type;
        return $this;
    }

}
