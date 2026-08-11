<?php

namespace App\Domain\Recipe\Repository;

use App\Domain\Recipe\Entity\Category;

interface CategoryRepositoryInterface
{
    /**
     * @param array<string, mixed> $criteria
     *
     * @return array<Category>
     */
    public function findBy(array $criteria): array;

    public function update(Category $category): void;

    /**
     * @return list<Category>
     */
    public function findAll(): array;
}
