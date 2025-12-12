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

#[Route('/auth', name: 'api_auth')]
final class UserController extends AbstractController
{
    // #[Route('/register', methods: ['POST'])]

    // public function register
    // (
    //     Request $req,
    //     UserPasswordHasherInterface $hasher,
    //     PromoRepository $promoRepo,
    //     RoleRepository $roleRepo,
    //     UserRepository $userRepo,
    //     EntityManagerInterface $em
    // ): JsonResponse 
    // { 
    //     try{
    //         $data = $req->getPayload()->all();
    //         $required = ['email', 'first_name', 'last_name', 'username', 'password', 'promo'];
    //         foreach($required as $field)
    //         {
    //             if (empty($data[$field])) {
    //                 return $this->json([
    //                     'success' => false,
    //                     'message' => "Missing field: {$field}"
    //                 ], 422);
    //             }
    //         }

    //         if (empty($data['promo']['city']) || empty($data['promo']['year'])) {
    //             return $this->json([
    //                 'success' => false,
    //                 'message' => 'Promo is required'
    //             ], 422);
    //         }

    //         if ($userRepo->findOneBy(['email' => $data['email']])) {
    //             return $this->json([
    //                 'success' => false,
    //                 'message' => 'Email already exists'
    //             ], 422);
    //         }

    //         if ($userRepo->findOneBy(['username' => $data['username']])) {
    //             return $this->json([
    //                 'success' => false,
    //                 'message' => 'Username already exists'
    //             ], 422);
    //         }
        
    //         $user = new User();
    //         $user->setEmail($data['email']);
    //         $user->setFirstName($data['first_name']);
    //         $user->setLastName($data['last_name']);
    //         $user->setUsername($data['username']);
    //         $user->setValidated(0);
    //         $user->setVerified(0);
    //         $user->setCreatedAt(new DateTimeImmutable("now"));
    
    //         // password hashing
    //         $user->setPasswordHash(
    //             $hasher->hashPassword(
    //                 $user, 
    //                 $data['password'])
    //         );
    
    //         // set promo
    //         $promo = $promoRepo->findOneBy([
    //             'city' => $data['promo']['city'],
    //             'year' => $data['promo']['year']
    //         ]);

    //         if(!$promo)
    //         {
    //             return $this->json([
    //                 'success' => false,
    //                 'message' => 'Invalid promo'
    //             ], 422);
    //         }

    //         $user->setPromo($promo);
    
    //         // set role
    //         $role = $roleRepo->findOneBy(['title' => 'user']);
    //         $user->setRole($role);
    
    //         // persist user creation
    //         $em->persist($user);
    //         $em->flush();
    
    //         // return json response
    //         return $this->json([
    //             'success' => true,
    //             'message' => 'Registration Successful, please check your email for the verification link',
    //             'email' => $user->getEmail(),
    //         ], 201);
            
    //     } 
    //     catch(\Exception $e) 
    //     {
    //         return $this->json([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function __construct(private readonly UserRegistrationService $registrationService, private readonly UserLoginService $loginService) {}

    
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

            $token = $authService->generateToken($result);
            
            $data = [
                'email' => $result['email'],
                'id' => $result['id'],
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
}


