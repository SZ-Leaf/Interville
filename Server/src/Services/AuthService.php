<?php

namespace App\Services;

use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Core\JWK;

class AuthService
{
   public function __construct(
      private readonly object $jwsBuilder
  ) {}
   /**
    * Generate a JWT token for a given user payload
    */
   public function generateToken(array $payload): string
   {
      // Create a symmetric key from environment variable
      $key = new JWK([
         'kty' => 'oct',
         'k' => $_ENV['JWT_SECRET']
      ]);

      // Create the JWS (signed JWT)
      $builder = $this->jwsBuilder->create(['HS256']);

      $jws = $builder
         ->create()
         ->withPayload(json_encode($payload))
         ->addSignature($key, ['alg' => 'HS256'])
         ->build();

      // Serialize to compact form
      return (new CompactSerializer())->serialize($jws);
   }

   /**
    * Placeholder for token verification (we'll implement later)
    */
   public function verifyToken(string $token): array
   {
      return [];
   }
}
