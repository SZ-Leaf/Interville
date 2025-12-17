<?php

namespace App\Services\User;

use App\DTO\User\UpdateUserRequest;
use App\Exception\User\UpdateUserException;
use App\Repository\UserRepository;
use App\Services\Auth\AuthService;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UserUpdateService
{
   public function __construct(
      private readonly UserRepository $userRepository,
      private readonly AuthService $authService,
      private readonly EntityManagerInterface $em,
      private readonly ValidatorInterface $validator,
   ){}

   public function updateUser(UpdateUserRequest $dto, string $token)
   {

      $violations = $this->validator->validate($dto);
      if(count($violations) > 0){
         $errs = [];
         foreach($violations as $v){
            $errs[] = sprintf('%s: %s', $v->getPropertyPath(), $v->getMessage());
         }
         throw new UpdateUserException(implode('; ', $errs));
      }
      
      $payload = $this->authService->verifyToken($token);
      if (!$payload || empty($payload['email'])) {
         throw new \RuntimeException('Invalid token payload');
      }

      $user = $this->userRepository->findOneBy(['email' => $payload['email']]);
      if(!$user)
      {
         throw UpdateUserException::userNotFound();
      }

      if($dto->firstName !== null && $dto->firstName !== '')
      {
         $user->setFirstName($dto->firstName);
      }

      if($dto->lastName !== null && $dto->lastName !== '')
      {
         $user->setLastName($dto->lastName);
      }

      if ($dto->username !== null && $dto->username !== '') {
         $existing = $this->userRepository->findOneBy(['username' => $dto->username]);
         if ($existing && $existing->getId() !== $user->getId()) {
             throw UpdateUserException::usernameExists();
         }
         $user->setUsername($dto->username);
      }

      $this->em->flush();
      
      return $user;
   }
}