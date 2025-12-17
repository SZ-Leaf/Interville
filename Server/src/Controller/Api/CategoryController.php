<?php

namespace App\Controller\Api;

use App\DTO\Category\UpdateCategoryRequest;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Category;
use App\DTO\Category\CreateCategoryRequest;

final class CategoryController extends AbstractController
{
    #[Route('/category/add', name: 'app_api_category_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        try {

            $data = json_decode($request->getContent(), true);

            $dto = new CreateCategoryRequest();
            $dto->name = trim($data['name']);

            $errors = $validator->validate($dto);

            if (count($errors) > 0) {
                $errorsMessages = [];

                foreach ($errors as $error) {
                    $errorsMessages[] = [
                        'property' => $error->getPropertyPath(),
                        'message' => $error->getMessage()
                    ];
                }
                return $this->json([
                    'success' => false,
                    'message' => $errorsMessages
                ], 400);
            }

            $category = new Category();
            $category->setTitle($dto->name);

            $em->persist($category);
            $em->flush();

            return $this->json([
                'success' => true,
                'id' => $category->getId(),
                'message' => "New category created with success !"
            ], 201);

        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => "Error while trying to create a new category : " . $e->getMessage()
            ], 500);
        }
    }

    #[Route('category/get/all', name: 'app_api_category_get_all', methods: ['GET'])]
    public function getAll(EntityManagerInterface $em)
    {
        try {
            $allCategories = $em->getRepository(Category::class)->findAll();

            if (count($allCategories) <= 0) {
                return $this->json([
                    'success' => true,
                    'message' => "There's no category in the database",
                    'data' => []
                ], 200);
            }

            return $this->json([
                'success' => true,
                'message' => "Categories returned with success",
                'data' => $allCategories
            ], 200);

        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => "Error while trying to get all categories : " . $e->getMessage()
            ], 500);
        }
    }

    #[Route('category/get/{id}', name: 'app_api_category_get_by_id', methods: ['GET'])]
    public function getById(int $id, EntityManagerInterface $em)
    {
        try {
            $category = $em->getRepository(Category::class)->find($id);

            if (!$category) {
                return $this->json([
                    'success' => false,
                    'message' => "Category not found",
                    'data' => (object) []
                ], 404);
            }

            return $this->json([
                'success' => true,
                'message' => "Category returned with success !",
                "data" => $category
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                "success" => false,
                "message" => "Error while to get the category : " . $e->getMessage(),
                "data" => (object) []
            ], 500);
        }
    }

    #[Route('/category/update/{id}', name: 'app_api_category_updatecategory', methods: ['PUT'])]
    public function update(int $id, EntityManagerInterface $em, Request $request, ValidatorInterface $validator)
    {
        try {
            $category = $em->getRepository(Category::class)->find($id);

            if (!$category) {
                return $this->json([
                    'success' => false,
                    'message' => "Category not found"
                ], 404);
            }

            $data = json_decode($request->getContent(), true);

            $dto = new UpdateCategoryRequest();
            $dto->name = trim($data['name']);

            $errors = $validator->validate($dto);

            if (count($errors) > 0) {
                $errorsMessages = [];
                foreach ($errors as $error) {
                    $errorsMessages[] = [
                        'property' => $error->getPropertyPath(),
                        'message' => $error->getMessage()
                    ];
                }
                return $this->json([
                    'success' => false,
                    'message' => $errorsMessages
                ], 400);
            }

            $category->setTitle($dto->name);
            $em->flush();

            return $this->json([
                'success' => true,
                'message' => "Category updated with success !"
            ], 200);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => "Error while trying to update the category : " . $e->getMessage()
            ], 500);
        }
    }

    #[Route('category/delete/{id}')]
    public function delete(int $id, EntityManagerInterface $em)
    {
        try {
            $category = $em->getRepository(Category::class)->find($id);

            if (!$category) {
                return $this->json([
                    'success' => false,
                    'message' => "Category not found"
                ], 404);
            }

            $em->remove($category);
            $em->flush();

            return $this->json([
                'success' => true,
                'message' => "Category deleted successfully"
            ], 200);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => "Error while trying to delete the category : " . $e->getMessage()
            ], 500);
        }
    }
}
