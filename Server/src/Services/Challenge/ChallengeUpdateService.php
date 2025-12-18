<?php

namespace App\Services\Challenge;

use App\DTO\Challenge\UpdateChallengeRequest;
use App\Exception\Challenge\UpdateChallengeException;
use App\Repository\CategoryRepository;
use App\Repository\ChallengeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ChallengeUpdateService
{
   public function __construct(
      private readonly EntityManagerInterface $em,
      private readonly ChallengeRepository $challengeRepository,
      private readonly UserRepository $userRepository,
      private readonly ValidatorInterface $validator,
      private readonly CategoryRepository $categoryRepository,
      private readonly ChallengeAuthorizationService $challengeAuthorizationService
   )
   {}

   public function updateChallenge(UpdateChallengeRequest $dto, int $challenge_id, int $user_id)
   {
      $violations = $this->validator->validate($dto);
      if(count($violations) > 0){
         $errs = [];
         foreach($violations as $v){
            $errs[] = sprintf('%s: %s', $v->getPropertyPath(), $v->getMessage());
         }
         throw new UpdateChallengeException(implode('; ', $errs));
      }

      $challenge = $this->challengeRepository->find($challenge_id);
      
      if(!$challenge){
         throw new \RuntimeException('Challenge not found');
      }

      $user = $this->userRepository->find($user_id);

      if(!$user){
         throw new AccessDeniedException('User not found');
      }

      $this->challengeAuthorizationService->assertCanModify($challenge, $user);

      if($dto->title !== null && $dto->title !== ''){
         $challenge->setTitle($dto->title);
      }
      if($dto->details !== null && $dto->details !== ''){
         $challenge->setDetails($dto->details);
      }
      if($dto->startDate !== null && $dto->startDate !== ''){
         $challenge->setStartDate(new \DateTime($dto->startDate));
      }
      if($dto->finishDate !== null && $dto->finishDate !== ''){
         $challenge->setFinishDate(new \DateTime($dto->finishDate));
      }
      if($dto->category !== null && $dto->category !== ''){
         $category = $this->categoryRepository->findOneBy(['title' => $dto->category]);
         if(!$category){
            throw new UpdateChallengeException('Category not found');
         }
         $challenge->setCategory($category);
      }

      $this->em->flush();

      return $challenge;
   }
}