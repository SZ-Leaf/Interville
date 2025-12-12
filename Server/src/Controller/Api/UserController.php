<?php

namespace App\Controller\Api;

use App\DTO\LoginUserRequest;
use App\DTO\RegisterUserRequest;
use App\Exception\LoginException;
use App\Exception\RegistrationException;
use App\Services\UserLoginService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\UserRegistrationService;
use Symfony\Component\HttpFoundation\Request;
use App\Services\AuthService;
use App\Services\ProfileService;

#[Route('/auth', name: 'api_auth')]
final class UserController extends AbstractController
{     
    #[Route('/register', methods: ['POST'])]
    public function register(Request $request, UserRegistrationService $registrationService): JsonResponse
    {
        try {
            $payload = $request->toArray();

            $dto = new RegisterUserRequest();
            $dto->email = $payload['email'] ?? null;
            $dto->firstName = $payload['first_name'] ?? null;
            $dto->lastName = $payload['last_name'] ?? null;
            $dto->username = $payload['username'] ?? null;
            $dto->password = $payload['password'] ?? null;
            $dto->promo = $payload['promo'] ?? null;

            $result = $registrationService->register($dto);

            return $this->json([
                'success' => true,
                'message' => 'Registration Successful, please check your email for the verification link',
                'email' => $result['email'],
            ], 201);
        } catch (RegistrationException $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Server error' . $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/login', methods: ['POST'])]
    public function login(Request $request, UserLoginService $loginService, AuthService $authService): JsonResponse
    {
        try {
            $payload = $request->toArray();

            $dto = new LoginUserRequest();
            $dto->email = $payload['email'] ?? null;
            $dto->password = $payload['password'] ?? null;


            $result = $loginService->login($dto);

            $tokenPayload = [
                'email' => $result['email'],
                'role' => "ROLE_" . strtoupper($result['role']),
            ];

            $token = $authService->generateToken($tokenPayload);
            
            $data = [
                'email' => $result['email'],
                'role' => "ROLE_" . strtoupper($result['role']),
            ];

            return $this->json(
                [
                    'success' => true,
                    'message' => 'Login Successful',
                    'data' => $data,
                    'token' => $token,
                ], 200);

        } catch (LoginException $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);

        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Server error' . $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/profile', methods: ['GET'])]
    public function profile(Request $request, ProfileService $profileService): JsonResponse
    {
        try {
            $token = $request->headers->get('Authorization');

            // Check if the header exists and starts with "Bearer "
            if (!$token || !str_starts_with($token, 'Bearer ')) {
                throw new \RuntimeException('Missing or invalid Authorization header');
            }
            
            $token = substr($token, 7); // 7 = length of "Bearer "

            $result = $profileService->getProfile($token);

            return $this->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'data' => $result,
            ], 200);

        } catch (\RuntimeException $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);

        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Server error' . $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/edit-profile', methods: ['PUT'])]
    public function editProfile(Request $request, ProfileService $profileService)
    {
        
    }
}
