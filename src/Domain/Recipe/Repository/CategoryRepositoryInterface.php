<?php
namespace App\Domain\Recipe\Repository;

use App\Domain\Recipe\Entity\Category;

interface CategoryRepositoryInterface
{
    public function findBy(array $criteria): array;

    public function update(Category $category): void;
}