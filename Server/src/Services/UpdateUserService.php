<?php

namespace App\Services;

use App\DTO\UpdateUserRequest;
use App\Exception\UpdateUserException;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateUserService
{
   public function __construct(
      private readonly UserRepository $userRepository,
      private readonly ValidatorInterface $validator,
      private readonly EntityManagerInterface $em,
   ){}

   public function updateUser(UpdateUserRequest $dto)
   {
      $violations = $this->validator->validate($dto);

      if(count($violations) > 0){
         $errs = [];
         foreach($violations as $v){
            $errs[] = sprintf('%s: %s', $v->getPropertyPath(), $v->getMessage());
         }
         throw new UpdateUserException(implode('; ', $errs));
      }

      
   }
}