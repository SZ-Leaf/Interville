<?php

namespace App\Services;

use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Core\JWK;
// use Jose\Component\Signature\JWSVerifierFactory;

class AuthService
{
   public function __construct(
      private readonly object $jwsBuilder,
      private readonly object $jwsVerifier
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
      // What it does: Creates an instance of the CompactSerializer class.
      // Purpose: JWTs are usually transmitted in compact serialized form, like a string
      // The serializer is responsible for turning that string into a JWS object (an internal PHP object representing the token).
      $serializer = new CompactSerializer();

      // What it does: Unserializes the token into a JWS object.
      // Purpose: The token is a compact serialized string, so we need to unserialize it into a JWS object.
      // This is where the token is converted from a string into a PHP object that can be used to verify the signature.
      $jws = $serializer->unserialize($token);

      // What it does: Creates a JWK (JSON Web Key) object.
      // Purpose: The JWK is used to verify the token signature.
      // The JWK is a JSON object that contains the key used to verify the token signature.
      $key = new JWK([
         'kty' => 'oct',
         'k' => $_ENV['JWT_SECRET']
      ]);

      // What it does: Verifies the token signature.
      // Purpose: The token signature is verified using the JWK.
      $isValid = $this->jwsVerifier->verifyWithKey($jws, $key, 0);

      if(!$isValid)
      {
         throw new \RuntimeException('Invalid token');
      }

      // What it does: Decodes the token payload.
      // Purpose: The token payload is decoded into an array.
      return json_decode($jws->getPayload(), true);
   }
}
