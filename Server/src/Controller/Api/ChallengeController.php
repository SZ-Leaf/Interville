<?php

namespace App\Controller\Api;

use App\DTO\Challenge\CreateChallengeRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Challenge;
use App\Exception\Challenge\CreateChallengeException;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Auth\AuthService;
use App\Services\Challenge\ChallengeCreateService;

#[Route('/challenges', name: 'app_api_challenge')]
final class ChallengeController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $challenges = $em->getRepository(Challenge::class)->findAll();
        return $this->json($challenges, 200);
    }

    #[Route('/create', methods: ['POST'])]
    public function create(Request $request, AuthService $authService, ChallengeCreateService $challengeCreateService): JsonResponse
    {
        try
        {
            $bearer = $request->headers->get('Authorization');
        
            if (!$bearer || !str_starts_with($bearer, 'Bearer ')) {
                throw new \RuntimeException('Missing or invalid Authorization header');
            }
    
            $token = substr($bearer, 7);
            $decoded = $authService->verifyToken($token);
            $owner = $decoded['email'];
    
            $payload = $request->toArray();
            $dto = new CreateChallengeRequest();
            $dto->title = !empty($payload['title']) ? trim($payload['title']) : null;
            $dto->details = !empty($payload['details']) ? trim($payload['details']) : null;
            $dto->startDate = !empty($payload['start_date']) ? trim($payload['start_date']) : null;
            $dto->finishDate = !empty($payload['finish_date']) ? trim($payload['finish_date']) : null;
            $dto->category = !empty($payload['category']) ? trim($payload['category']) : null;

            $createdChallenge = $challengeCreateService->createChallenge($dto, $owner);

            $data = [
                'title' => $createdChallenge->getTitle(),
                'details' => $createdChallenge->getDetails(),
                'category' => $createdChallenge->getCategory()?->getTitle(),
                'created_at' => $createdChallenge->getCreatedAt(),
                'start_date' => $createdChallenge->getStartDate(),
            ];

            return $this->json([
                'success' => true,
                'message' => 'Challenge created successfully',
                'data' => $data,
            ], 201);

        }
        catch (CreateChallengeException $err){
            return $this->json([
                'success' => false,
                'message' => $err->getMessage(),
            ], 422);

        }
        catch (\RuntimeException $err){
            return $this->json([
                'success' => false,
                'message' => $err->getMessage(),
            ], 401);

        }
        catch (\Throwable $err){
            return $this->json([
                'success' => false,
                'message' => 'Server error' . $err->getMessage(),
            ], 500);
        }
    }

    #[Route('/update-challenge', methods: ['PUT'])]
    public function updateChallenge(Request $request, AuthService $authService)
    {

    }

    #[Route('/delete', method: 'DELETE')]
    public function deleteChallenge(Request $request, AuthService $authService, EntityManagerInterface $em)
    {
        
    }
}
