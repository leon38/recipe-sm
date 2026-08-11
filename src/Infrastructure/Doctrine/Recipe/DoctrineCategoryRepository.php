<?php

namespace App\Infrastructure\Doctrine\Recipe;

use App\Domain\Recipe\Entity\Category;
use App\Domain\Recipe\Repository\CategoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Category>
 */
final class DoctrineCategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private \Doctrine\ORM\EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param array<string, mixed> $criteria
     *
     * @return Category[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    public function update(Category $category): void
    {
        $this->entityManager->persist($category);
    }

    /**
     * @return list<Category>
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}
