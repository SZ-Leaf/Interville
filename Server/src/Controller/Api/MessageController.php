<?php

namespace App\Controller\Api;

use App\DTO\Message\CreateMessageRequest;
use App\Entity\ChatMessage;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Services\Auth\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Jose\Component\KeyManagement\Analyzer\Message;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class MessageController extends AbstractController
{
    #[Route('message/add', name: 'app_api_message_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, AuthService $authService, UserRepository $userRepository)
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

            $ownerEmail = $decoded['email'];
            $owner = $userRepository->findOneBy(['email' => $ownerEmail]);

            $data = json_decode($request->getContent(), true);

            $dto = new CreateMessageRequest();
            $dto->content = trim($data['content']);

            $errors = $validator->validate($dto);

            if (count($errors) > 0) {
                $errorsMessage = [];
                foreach ($errors as $error) {
                    $errorsMessage[] = [
                        'property' => $error->getPropertyPath(),
                        "message" => $error->getMessage()
                    ];
                }
                return $this->json([
                    'success' => false,
                    'message' => $errorsMessage
                ], 400);
            }

            $message = new ChatMessage();
            $message->setContent($dto->content);
            $message->setUser($owner);

            $em->persist($message);
            $em->flush();

            return $this->json([
                'success' => true,
                'message' => "Chat message successfully sent"
            ], 201);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => "Error while trying to create the message : " . $e->getMessage(),
                'log' => $decoded
            ]);
        } catch (\RuntimeException $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    #[Route('message/delete/{id}', name: 'app_api_message_delete', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, AuthService $authService, UserRepository $userRepository, int $id)
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

            $ownerEmail = $decoded['email'];
            $owner = $em->getRepository(User::class)->findOneBy(['email' => $ownerEmail]);
            $ownerRole = $owner->getRole()->getTitle();

            $message = $em->getRepository(ChatMessage::class)->find($id);
            if (!$message) {
                return $this->json([
                    'success' => false,
                    'message' => "Message not found !"
                ], 404);
            }

            $messageOwner = $message->getUser();

            if ($owner != $messageOwner && $ownerRole != "admin" && $ownerRole != "mod") {
                return $this->json([
                    'success' => false,
                    'message' => "Unauthorized"
                ], 401);
            }

            return $this->json([
                'success' => true,
                'message' => "Message deleted with success !"
            ]);
        } catch (\Throwable $e) {
            return $this->json([
               'success' => false,
               'message' => "Error while trying to delete the message : " . $e->getMessage() 
            ]);
        }
    }
}
