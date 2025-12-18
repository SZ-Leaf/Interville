<?php

namespace App\Services\User;

use App\DTO\User\LoginUserRequest;
use App\Exception\User\LoginException;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UserLoginService
{
   public function __construct(
      private readonly UserRepository $userRepository,
      private readonly UserPasswordHasherInterface $hasher,
      private readonly ValidatorInterface $validator
   ) {}

   /**
    * @throws LoginException
    */

   public function login(LoginUserRequest $dto)
   {
      $violations = $this->validator->validate($dto);
      if(count($violations) > 0){
         $errs = [];
         foreach($violations as $v){
            $errs[] = sprintf('%s: %s', $v->getPropertyPath(), $v->getMessage());
         }
         throw new LoginException(implode('; ', $errs));
      }

      $user = $this->userRepository->findOneBy(['email' => $dto->email]);
      if(!$user){
         throw LoginException::invalidCredentials();
      }

      if(!$this->hasher->isPasswordValid($user, $dto->password)){
         throw LoginException::invalidCredentials();
      }

      return [
         'email' => $user->getEmail(),
         'role' => strtoupper($user->getRole()->getTitle()),
         'id' => $user->getId(),
      ];
   }
}