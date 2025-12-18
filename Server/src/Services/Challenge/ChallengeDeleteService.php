<?php

namespace App\Services\Challenge;

use App\Repository\ChallengeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


final class ChallengeDeleteService
{
   public function __construct(
      private readonly ChallengeRepository $challengeRepository,
      private readonly EntityManagerInterface $em,
      private readonly UserRepository $userRepository
   )
   {}

   public function deleteChallenge(int $challenge_id, int $user_id)
   {
      $challenge = $this->challengeRepository->find($challenge_id);

      if (!$challenge) {
         throw new \RuntimeException('Challenge not found');
      };

      $user = $this->userRepository->find($user_id);

      if (!$user) {
         throw new AccessDeniedException('User not found');
      }

      $isOwner = $user->getId() === $challenge->getOwner()->getId();
      $isAdminOrMod = 
                     in_array('ROLE_ADMIN', $user->getRoles(), true)
                  || in_array('ROLE_MOD', $user->getRoles(), true);

      if (!$isOwner && !$isAdminOrMod) {
         throw new AccessDeniedException('User not allowed to delete the challenge');
      }

      $this->em->remove($challenge);
      $this->em->flush();
   }
}