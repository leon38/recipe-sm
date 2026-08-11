<?php

namespace App\Application\Recipe\Mapper;

use App\Application\Recipe\Response\CategoryResponse;
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
