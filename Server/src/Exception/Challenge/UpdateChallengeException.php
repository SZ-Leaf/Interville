<?php

namespace App\Exception\Challenge;

use RuntimeException;

final class UpdateChallengeException extends RuntimeException
{
   public static function invalidTitle(): self
   {
      return new self('Invalid title.');
   }

   public static function invalidDetails(): self
   {
      return new self('Invalid details.');
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
}