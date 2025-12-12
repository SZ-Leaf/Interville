<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserRequest
{
   #[Assert\NotBlank(allowNull: true)]
   public ?string $firstName = null;

   #[Assert\NotBlank(allowNull: true)]
   public ?string $lastName = null;

   #[Assert\NotBlank(allowNull: true)]
   public ?string $username = null;
}
