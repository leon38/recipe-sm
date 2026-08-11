<?php
namespace App\Domain\Recipe\Repository;

use App\Domain\Recipe\Entity\Category;

final class DoctrineCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private \Doctrine\ORM\EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param array<string, mixed> $criteria
     * @return Category[]
     */
    public function findBy(array $criteria): array
    {
        return $this->entityManager->getRepository(Category::class)->findBy($criteria);
    }

    public function update(Category $category): void
    {
        $this->entityManager->persist($category);
    }
}