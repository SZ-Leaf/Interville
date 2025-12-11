<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/auth', name: 'api_auth')]
final class UserController extends AbstractController
{
    #[Route('/index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Response $res, EntityManagerInterface $em): JsonResponse
    {
        $users = $userRepository->findAll();
        return $this->json($users);
    }

    #[Route('/register', methods: ['POST'])]
    public function register(UserRepository $userRepository, Request $req) 
    { 
        // $data = $req->getPayload()->all();
        
        // $user = new User();
        // $user->setEmail($data['email'] ?? '');
        // $user->setFirstName($data['first_name'] ?? '');
        // $user->setLastName($data['last_name'] ?? '');
        // $user->setValidated(0);
        // $user->setVerified(0);
        // $user->
    }
}