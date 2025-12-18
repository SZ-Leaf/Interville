<?php

namespace App\DTO\Challenge;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateChallengeRequest
{
   #[Assert\NotBlank(AllowNull: true)]
   #[Assert\Length(min: 3, max: 255)]
   public string $title;

   #[Assert\NotBlank(allowNull: true)]
   #[Assert\Length(min: 10, max: 1000)]
   public ?string $details = null;

   #[Assert\NotBlank(AllowNull: true)]
   // #[Assert\Type(type: 'datetime')]
   public ?string $startDate = null;

   #[Assert\NotBlank(AllowNull: true)]
   // #[Assert\Type(type: 'datetime')]
   public ?string $finishDate = null;

   #[Assert\NotBlank(AllowNull: true)]
   public ?string $category = null;
}