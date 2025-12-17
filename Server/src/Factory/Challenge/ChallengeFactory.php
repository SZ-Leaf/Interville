<?php

namespace App\Factory\Challenge;

use App\Enums\StatusType;
use App\Entity\Challenge;
use App\Entity\Category;
use App\Entity\User;
use DateTimeImmutable;

class ChallengeFactory
{
   public function createFromData(
      string $title,
      string $details,
      \DateTime $startDate,
      \DateTime $finishDate,
      Category $category,
      User $owner,
   ): Challenge {
      $challenge = new Challenge();
      $challenge->setTitle($title);
      $challenge->setDetails($details);
      $challenge->setStartDate($startDate);
      $challenge->setFinishDate($finishDate);
      $challenge->setCategory($category);
      $challenge->setOwner($owner);
      $challenge->setStatus(StatusType::WAITING);
      $challenge->setCreatedAt(new DateTimeImmutable('now'));
      return $challenge;
   }
}