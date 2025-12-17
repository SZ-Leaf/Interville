<?php

namespace App\Exception\Challenge;

use RuntimeException;

final class CreateChallengeException extends RuntimeException
{
   public static function invalidTitle(): self
   {
      return new self('Invalid title.');
   }

   public static function invalidDetails(): self
   {
      return new self('Invalid details.');
   }

   public static function invalidStatus(): self
   {
      return new self('Invalid status.');
   }

   public static function invalidStartDate(): self
   {
      return new self('Invalid start date.');
   }

   public static function invalidFinishDate(): self
   {
      return new self('Invalid finish date.');
   }

   public static function invalidCategory(): self
   {
      return new self('Invalid category.');
   }

   public static function invalidUser() : self
   {
      return new self('Invalid User');
   }
}