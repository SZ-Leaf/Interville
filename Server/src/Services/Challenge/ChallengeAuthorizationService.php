<?php

namespace App\Services\Challenge;

use App\Entity\Challenge;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class ChallengeAuthorizationService
{
   public function assertCanModify(Challenge $challenge, User $user): void
   {
      $isOwner = $user->getId() === $challenge->getOwner()->getId();
      $isAdminOrMod =
         in_array('ROLE_ADMIN', $user->getRoles(), true)
      || in_array('ROLE_MOD', $user->getRoles(), true);

      if (!$isOwner && !$isAdminOrMod) {
         throw new AccessDeniedException('User not allowed to modify this challenge');
      }
   }
}
