<?php

namespace App\DTO\Challenge;

use Symfony\Component\Validator\Constraints as Assert;

class CreateChallengeRequest
{
   #[Assert\NotBlank]
   #[Assert\Length(min: 3, max: 255)]
   public string $title;

   #[Assert\NotBlank]
   #[Assert\Length(min: 10, max: 1000)]
   public ?string $details = null;

   #[Assert\NotBlank]
   // #[Assert\Type(type: 'datetime')]
   public ?string $startDate = null;

   #[Assert\NotBlank]
   // #[Assert\Type(type: 'datetime')]
   public ?string $finishDate = null;

   #[Assert\NotBlank]
   public ?string $category = null;
}