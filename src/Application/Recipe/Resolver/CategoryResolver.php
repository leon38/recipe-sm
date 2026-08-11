<?php

namespace App\Application\Recipe\Resolver;

use App\Domain\Recipe\Entity\Category;
use App\Domain\Recipe\Repository\CategoryRepositoryInterface;

final class CategoryResolver
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    /**
     * @param array<array<string, string>> $categories
     *
     * @return Category[]
     */
    public function resolve(array $categories): array
    {
        $existingCategories = $this->categoryRepository->findBy(
            ['id' => array_column($categories, 'id')],
        );

        $categoryIds = array_map(fn (Category $category) => $category->getId()->getValue(), $existingCategories);

        foreach ($categories as $categoryData) {
            if (!in_array($categoryData['id'], $categoryIds)) {
                $existingCategories[] = Category::create(
                    name: $categoryData['name'],
                );
            }
        }

        return $existingCategories;
    }
}
