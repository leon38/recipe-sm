<?php
namespace App\Application\Recipe\Mapper;

use App\Domain\Recipe\Entity\Category;
use App\Application\Recipe\Response\CategoryResponse;

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