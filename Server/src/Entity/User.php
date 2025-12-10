<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "`users`")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private string $email;

    #[ORM\Column(length: 100)]
    private string $firstName;

    #[ORM\Column(length: 100)]
    private string $lastName;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(length: 50, unique: true)]
    private string $username;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Promo $promo;

    #[ORM\ManyToOne(inversedBy: 'roleUsers')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Role $role;

    #[ORM\Column]
    private bool $validated = false;

    #[ORM\Column]
    private bool $verified = false;

    /**
     * @var Collection<int, ChatMessage>
     */
    #[ORM\OneToMany(targetEntity: ChatMessage::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $chatMessages;

    /**
     * @var Collection<int, Challenge>
     */
    #[ORM\OneToMany(targetEntity: Challenge::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $challenges;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'participant')]
    private Collection $participations;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->chatMessages = new ArrayCollection();
        $this->challenges = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getPromo(): Promo
    {
        return $this->promo;
    }

    public function setPromo(Promo $promo): static
    {
        $this->promo = $promo;
        return $this;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function isValidated(): bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): static
    {
        $this->validated = $validated;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;
        return $this;
    }

    /**
     * @return Collection<int, ChatMessage>
     */
    public function getChatMessages(): Collection
    {
        return $this->chatMessages;
    }

    public function addChatMessage(ChatMessage $chatMessage): static
    {
        if (!$this->chatMessages->contains($chatMessage)) {
            $this->chatMessages->add($chatMessage);
            $chatMessage->setUser($this);
        }
        return $this;
    }

    public function removeChatMessage(ChatMessage $chatMessage): static
    {
        $this->chatMessages->removeElement($chatMessage);
        return $this;
    }

    /**
     * @return Collection<int, Challenge>
     */
    public function getChallenges(): Collection
    {
        return $this->challenges;
    }

    public function addChallenge(Challenge $challenge): static
    {
        if (!$this->challenges->contains($challenge)) {
            $this->challenges->add($challenge);
            $challenge->setOwner($this);
        }

        return $this;
    }

    public function removeChallenge(Challenge $challenge): static
    {
        $this->challenges->removeElement($challenge);
        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setParticipant($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        $this->participations->removeElement($participation);
        return $this;
    }
}
