<?php

namespace App\Exception;

use RuntimeException;

final class LoginException extends RuntimeException
{
   public static function invalidCredentials(): self
   {
      return new self('Invalid credentials.');
   }
}