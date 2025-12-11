<?php

namespace App\Entity;

use App\Enums\ParticipationStatus;
use App\Repository\ParticipationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
#[ORM\Table(
    name: "participations",
    uniqueConstraints: [
        new ORM\UniqueConstraint(
            name: 'uniq_participation_user_challenge',
            columns: ['user_id', 'challenge_id']
        )
    ]
)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(name: 'challenge_id', nullable: false, onDelete: 'CASCADE')]
    private Challenge $challenge;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: Types::STRING, enumType: ParticipationStatus::class)]
    private ParticipationStatus $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChallenge(): Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(Challenge $challenge): static
    {
        $this->challenge = $challenge;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ParticipationStatus
    {
        return $this->status;
    }

    public function setStatus(ParticipationStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}
