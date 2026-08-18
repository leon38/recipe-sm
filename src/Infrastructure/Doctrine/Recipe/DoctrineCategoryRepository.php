<?php

namespace App\Infrastructure\Doctrine\Recipe;

use App\Domain\Recipe\Entity\Category;
use App\Domain\Recipe\Repository\CategoryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
final class DoctrineCategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(private ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
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
        $this->registry->getManager()->persist($category);
    }

    /**
     * @return list<Category>
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}
