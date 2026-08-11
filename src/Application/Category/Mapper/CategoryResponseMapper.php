<?php

namespace App\Application\Category\Mapper;

use App\Application\Category\Response\CategoryResponse;
use App\Domain\Recipe\Entity\Category;

final class CategoryResponseMapper
{
    public static function map(Category $category): CategoryResponse
    {
        return new CategoryResponse(
            id: (string) $category->getId(),
            name: $category->getName(),
        );
    }
}
