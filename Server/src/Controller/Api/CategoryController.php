<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Category;
use App\DTO\CreateCategoryRequest;

final class CategoryController extends AbstractController
{
    #[Route('/category/add', name: 'app_api_category_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator) : JsonResponse {
        try {
            
            $data = json_decode($request->getContent(), true);
            
            $dto = new CreateCategoryRequest();
            $dto->name = $data['name'];

            $errors = $validator->validate($dto);

            if (count($errors) > 0) {
                $errorsMessages = [];

                foreach($errors as $error) {
                    $errorsMessages[] = [
                        'property' => $error->getPropertyPath(),
                        'message' => $error->getMessage()
                    ];
                }
                return $this->json([
                    'success' => false,
                    'message' => $errorsMessages
                ]);
            }

            $category = new Category();
            $category->setTitle($dto->name);

            $em->persist($category);
            $em->flush();

            return $this->json([
                'success' => true,
                'id' => $category->getId(),
                'message' => "New category created with success !"
            ]);

        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => "Error while trying to create a new category : " . $e->getMessage()
            ]);
        }
    }

    #[Route('category/get/all', name: 'app_api_category_get_all', methods: ['GET'])]
    public function getAll(EntityManagerInterface $em) {
        try {
            $allCategories = $em->getRepository(Category::class)->findAll();

            if (count($allCategories) <= 0) {
                return $this->json([
                    'success' => true,
                    'message' => "There's no category in the database",
                    'data' => []
                ]);
            }

            return $this->json([
                'success' => true,
                'message' => "Categories returned with success",
                'data' => $allCategories
            ]);

        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => "Error while trying to get all categories : " . $e->getMessage()
            ]);
        }
    }
}
