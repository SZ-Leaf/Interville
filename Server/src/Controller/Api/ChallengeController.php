<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Challenge;
use Symfony\Component\HttpFoundation\Request;

#[Route('/challenges', name: 'app_api_challenge')]
final class ChallengeController extends AbstractController
{
    #[Route('/', name: 'app_api_challenge', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $challenges = $em->getRepository(Challenge::class)->findAll();
        return $this->json($challenges);
    }

    // #[Route('/create', methods: ['POST'])]
    // public function create(Request $request, EntityManagerInterface $em): JsonResponse
    // {

    // }
}
