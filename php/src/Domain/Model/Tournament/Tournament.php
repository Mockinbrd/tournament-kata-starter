<?php

namespace App\Domain\Model\Tournament;

use App\Domain\Model\Participant\Participant;
use Symfony\Component\Uid\Ulid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TournamentRepository::class)
 */
class Tournament
{
    public const TYPE_SINGLE_BRACKET = 'SingleBracketElimination';

    public const TYPES = [
        self::TYPE_SINGLE_BRACKET
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator("doctrine.ulid_generator")
     * @Groups({"tournament:read"})
     */
    private ?Ulid $id = null;

    /**
     * @ORM\Column(type="string")
     * @Groups({"tournament:read"})
     */
    private string $name = '';

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"tournament:read"})
     */
    private ?string $type = null;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, inversedBy="tournaments")
     */
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }
}
