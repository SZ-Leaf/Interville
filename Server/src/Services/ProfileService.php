<?php

namespace App\Services;

use App\Repository\UserRepository;

final class ProfileService
{
   public function __construct(
      private readonly AuthService $authService,
      private readonly UserRepository $userRepository
   ){}

   public function getProfile(string $token)
   {
      $payload = $this->authService->verifyToken($token);

      $user = $this->userRepository->findOneBy(['email' => $payload['email']]);

      if(!$user)
      {
         throw new \RuntimeException('User not found');
      }

      return [
         'email' => $user->getEmail(),
         'first_name' => $user->getFirstName(),
         'last_name' => $user->getLastName(),
         'username' => $user->getUsername(),
         'promo' => $user->getPromo()?->getCity() . ' ' . $user->getPromo()?->getYear() ?? null,
         'role' => "ROLE_" . strtoupper($user->getRole()->getTitle()),
         'validated' => $user->isValidated(),
         'verified' => $user->isVerified(),
         'created_at' => $user->getCreatedAt()?->format('Y-m-d H:i:s') ?? null,
      ];
   }
}