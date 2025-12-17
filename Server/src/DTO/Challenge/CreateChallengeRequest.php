<?php

namespace App\DTO\Challenge;

use App\Enums\StatusType;
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
   #[Assert\Choice(choices: StatusType::cases())]
   public ?StatusType $status = null;

   #[Assert\NotBlank]
   #[Assert\Type(type: 'datetime')]
   public ?\DateTime $startDate = null;

   #[Assert\Type(type: 'datetime')]
   public ?\DateTime $finishDate = null;

   #[Assert\NotBlank]
   public ?string $category = null;
}