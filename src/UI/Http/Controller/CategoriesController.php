<?php

namespace App\UI\Http\Controller;

use App\Application\Recipe\Mapper\CategoryResponseMapper;
use App\Domain\Recipe\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/category')]
final class CategoriesController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function __invoke(EntityManagerInterface $entityManager): JsonResponse
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->json(
            array_map(static fn (Category $category): array => CategoryResponseMapper::map($category)->jsonSerialize(), $categories), 200);
    }
}
