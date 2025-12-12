<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class LoginUserRequest
{
   #[Assert\NotBlank]
   #[Assert\Email]
   public ?string $email = null;

   #[Assert\NotBlank]
   #[Assert\Length(min: 8)]
   public ?string $password = null;

   // public function getEmail(): ?string
   // {
   //    return $this->email ?? null;
   // }

   // public function getPasswordHash(): ?string
   // {
   //    return $this->password ?? null;
   // }
}