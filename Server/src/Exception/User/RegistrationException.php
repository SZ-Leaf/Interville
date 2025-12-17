<?php
namespace App\Exception\User;

use RuntimeException;

final class RegistrationException extends RuntimeException
{
   public static function missingPromo(): self
   {
      return new self('Promo is required.');
   }

   public static function invalidPromo(): self
   {
      return new self('Invalid promo.');
   }

   public static function emailExists(): self
   {
      return new self('Email already exists.');
   }

   public static function usernameExists(): self
   {
      return new self('Username already exists.');
   }
}
