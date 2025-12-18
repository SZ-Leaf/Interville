<?php

namespace App\Controller\Api;

use App\Services\Auth\AuthService;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Promo;
use App\DTO\Promo\CreatePromoRequest;

final class PromoController extends AbstractController
{

    #[Route('promo/add', name: 'app_api_promo_create', methods: ['POST'])]
    public function create(Request $request, AuthService $authService, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        try {

            $bearer = $request->headers->get('Authorization');

            if (!$bearer || !str_starts_with($bearer, 'Bearer ')) {
                return $this->json([
                    'success' => false,
                    'message' => 'Missing or invalid Authorization header'
                ]);
            }

            $token = substr($bearer, 7); // 7 = length of "Bearer "
            $decoded = $authService->verifyToken($token);

            $userEmail = $decoded['email'];
            $user = $em->getRepository(User::class)->findOneBy(['email' => $userEmail]);
            $userRole = $user->getRole()->getTitle();

            if ($userRole === "user") {
                return $this->json([
                    'success' => false,
                    'message' => "Unauthorized"
                ], 401);
            }


            $data = json_decode($request->getContent(), true);

            $dto = new CreatePromoRequest();
            $dto->city = trim($data['city']);
            $dto->year = $data['year'];


            $errors = $validator->validate($dto);

            if (count($errors) > 0) {
                $errorsMessages = [];
                foreach ($errors as $error) {
                    $errorsMessages[] = [
                        'property' => $error->getPropertyPath(),
                        'message' => $error->getMessage(),
                    ];
                }
                return $this->json([
                    'success' => false,
                    'errors' => $errorsMessages,
                ], 400);
            }

            $promo = new Promo();
            $promo->setCity($dto->city);
            $promo->setYear($dto->year);

            $em->persist($promo);
            $em->flush();

            return $this->json([
                'success' => true,
                'id' => $promo->getId(),
                'message' => 'New promo created with success !'
            ], 201);

        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error while trying to create a new promo : ' . $e->getMessage(),
            ], 500);
        }

    }

    #[Route('promo/delete/{id}', name: 'app_api_promo_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em,Request $request,AuthService $authService, ValidatorInterface $validator)
    {
        try {
            $bearer = $request->headers->get('Authorization');

            if (!$bearer || !str_starts_with($bearer, 'Bearer ')) {
                return $this->json([
                    'success' => false,
                    'message' => 'Missing or invalid Authorization header'
                ]);
            }

            $token = substr($bearer, 7); // 7 = length of "Bearer "
            $decoded = $authService->verifyToken($token);

            $userEmail = $decoded['email'];
            $user = $em->getRepository(User::class)->findOneBy(['email' => $userEmail]);
            $userRole = $user->getRole()->getTitle();

            if ($userRole === "user") {
                return $this->json([
                    'success' => false,
                    'message' => "Unauthorized"
                ], 401);
            }


            $promo = $em->getRepository(Promo::class)->find($id);

            if (!$promo) {
                return $this->json([
                    'success' => false,
                    'message' => 'Promo not found'
                ], 404);
            }
            $em->remove($promo);
            $em->flush();

            return $this->json([
                'success' => true,
                'message' => 'Promo deleted successfully'
            ], 200);

        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => "Error while trying to delete the promo : " . $e->getMessage()
            ]);
        }

    }

    #[Route('promo/get/all', name: 'app_api_promo_get_all', methods: ['GET'])]
    public function getAll(EntityManagerInterface $em)
    {
        try {
            $allPromo = $em->getRepository(Promo::class)->findAll();

            if (count($allPromo) <= 0) {
                return $this->json([
                    'success' => true,
                    'message' => "There's no promo in the database",
                    "data" => []
                ], 200);
            }

            return $this->json([
                'success' => true,
                'message' => "Promos returned with success",
                'data' => $allPromo
            ]);

        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => "Error while trying to get all promos : " . $e->getMessage(),
                'data' => []
            ]);
        }

    }

}
