<?php
namespace App\UI\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Recipe\Entity\Category;
use App\Application\Recipe\Mapper\CategoryResponseMapper;

#[Route('/api/category')]
final class CategoriesController extends AbstractController
{

    #[Route("/", methods: ["GET"])]
    public function __invoke(EntityManagerInterface $entityManager): JsonResponse
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->json(
            array_map(static fn(Category $category): array => CategoryResponseMapper::map($category)->jsonSerialize(), $categories)
        , 200);
    }
}