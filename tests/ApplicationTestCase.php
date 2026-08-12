<?php

namespace App\Tests;

use App\Domain\Recipe\Repository\CategoryRepositoryInterface;
use App\Domain\Recipe\Repository\IngredientRepositoryInterface;
use App\Domain\Recipe\Repository\RecipeRepositoryInterface;
use App\Domain\Recipe\Repository\TagRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class ApplicationTestCase extends TestCase
{
    protected RecipeRepositoryInterface|MockObject $recipeRepository;
    protected IngredientRepositoryInterface|MockObject $ingredientRepository;
    protected CategoryRepositoryInterface|MockObject $categoryRepository;
    protected TagRepositoryInterface|MockObject $tagRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recipeRepository = $this->createMock(RecipeRepositoryInterface::class);
        $this->ingredientRepository = $this->createStub(IngredientRepositoryInterface::class);
        $this->categoryRepository = $this->createStub(CategoryRepositoryInterface::class);
        $this->tagRepository = $this->createStub(TagRepositoryInterface::class);
    }
}
