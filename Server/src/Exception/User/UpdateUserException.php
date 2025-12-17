<?php

namespace App\Exception\User;

use RuntimeException;

final class UpdateUserException extends RuntimeException
{
   public static function userNotFound(): self
   {
      return new self('User not found.');
   }

   public static function invalidFirstName(): self
   {
      return new self('Invalid first name.');
   }

   public static function invalidLastName(): self
   {
      return new self('Invalid last name.');
   }

   public static function invalidUsername(): self
   {
      return new self('Invalid username.');
   }

   public static function usernameExists(): self
   {
      return new self('Username already exists.');
   }
}