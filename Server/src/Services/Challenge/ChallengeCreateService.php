<?php

namespace App\Services\Challenge;

use App\DTO\Challenge\CreateChallengeRequest;
use App\Exception\Challenge\CreateChallengeException;
use App\Factory\Challenge\ChallengeFactory;
use App\Repository\CategoryRepository;
use App\Repository\ChallengeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ChallengeCreateService
{
   public function __construct(
      private readonly ChallengeRepository $challengeRepository,
      private readonly ChallengeFactory $challengeFactory,
      private readonly EntityManagerInterface $em,
      private readonly ValidatorInterface $validator,
      private readonly CategoryRepository $categoryRepository,
      private readonly UserRepository $userRepository,

   ) {}

   /**
    * @throws CreateChallengeException
    */

   public function createChallenge(CreateChallengeRequest $dto, string $owner_email)
   {
      $violations = $this->validator->validate($dto);
      if (count($violations) > 0) {
         $errs = [];
         foreach ($violations as $v) {
               $errs[] = sprintf('%s: %s', $v->getPropertyPath(), $v->getMessage());
         }
         throw new CreateChallengeException(implode('; ', $errs));
      }

      $category = $this->categoryRepository->findOneBy(['title' => $dto->category]);
      if (!$category) {
         throw CreateChallengeException::invalidCategory();
      }
      
      $owner = $this->userRepository->findOneBy(['email' => $owner_email]);
      if (!$owner){
         throw CreateChallengeException::invalidUser();
      }

      $challenge = $this->challengeFactory->createFromData(
         $dto->title,
         $dto->details,
         new \DateTime($dto->startDate . ' 00:00:00'),
         new \DateTime($dto->finishDate . ' 23:59:59'),
         $category,
         $owner,
      );

      $this->em->persist($challenge);
      $this->em->flush();

      return $challenge;
   }
}