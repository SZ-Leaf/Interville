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
      private readonly UserRepository $userRepository,
      private readonly ChallengeAuthorizationService $challengeAuthorizationService
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

      $this->challengeAuthorizationService->assertCanModify($challenge, $user);

      $this->em->remove($challenge);
      $this->em->flush();
   }
}