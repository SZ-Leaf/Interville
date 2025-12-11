<?php
namespace App\Factory;

use App\Entity\User;
use App\Entity\Promo;
use App\Entity\Role;
use DateTimeImmutable;


class UserFactory
{

   public function createFromData(
      string $email,
      string $firstName,
      string $lastName,
      string $username,
      string $password,
      Promo $promo,
      Role $role
   ): User {
      $user = new User();
      $user->setEmail($email);
      $user->setFirstName($firstName);
      $user->setLastName($lastName);
      $user->setUsername($username);
      $user->setPasswordHash($password);
      $user->setPromo($promo);
      $user->setRole($role);
      $user->setValidated(0);
      $user->setVerified(0);
      $user->setCreatedAt(new DateTimeImmutable('now'));

      return $user;
   }
}
