<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserRequest
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\NotBlank]
    public ?string $firstName = null;

    #[Assert\NotBlank]
    public ?string $lastName = null;

    #[Assert\NotBlank]
    public ?string $username = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public ?string $password = null;

    #[Assert\NotBlank]
    public ?array $promo = null;

    // convenience getters
    public function getPromoCity(): ?string
    {
        return $this->promo['city'] ?? null;
    }

    public function getPromoYear(): ?int
    {
        return isset($this->promo['year']) ? (int)$this->promo['year'] : null;
    }
}
