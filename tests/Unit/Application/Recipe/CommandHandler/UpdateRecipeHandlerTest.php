<?php

declare(strict_types=1);

namespace App\Tests\Application\Recipe\CommandHandler;

use App\Application\Recipe\CommandHandler\UpdateRecipeHandler;
use App\Application\Recipe\Factory\RecipeUpdater;
use App\Domain\Recipe\ValueObject\ValueId;
use App\Tests\ApplicationTestCase;
use App\Tests\Builder\RecipeBuilder;
use App\Tests\Builder\UpdateRecipeCommandBuilder;
use PHPUnit\Framework\MockObject\MockObject;

final class UpdateRecipeHandlerTest extends ApplicationTestCase
{
    private RecipeUpdater|MockObject $updater;
    private UpdateRecipeHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->updater = $this->createMock(RecipeUpdater::class);
        $this->handler = new UpdateRecipeHandler($this->updater, $this->recipeRepository);
    }

    public function testItUpdatesExistingRecipeFromCommandAndSavesIt(): void
    {
        $existingRecipe = RecipeBuilder::create()
            ->withTitle('Ancien nom')
            ->build();

        $updatedRecipe = RecipeBuilder::create()
            ->withTitle('Nouveau nom')
            ->build();

        $command = UpdateRecipeCommandBuilder::create()
            ->withId($existingRecipe->getId()->getValue())
            ->withTitle('Nouveau nom')
            ->build();

        $this->recipeRepository
            ->expects($this->once())
            ->method('get')
            ->with($this->isInstanceOf(ValueId::class))
            ->willReturn($existingRecipe);

        $this->updater
            ->expects($this->once())
            ->method('update')
            ->with($existingRecipe, $command)
            ->willReturn($updatedRecipe);

        $this->recipeRepository
            ->expects($this->once())
            ->method('save')
            ->with($updatedRecipe);

        $result = ($this->handler)($command);

        self::assertSame($updatedRecipe, $result);
    }

    public function testItPropagatesExceptionWhenRecipeIsNotFound(): void
    {
        $command = UpdateRecipeCommandBuilder::create()->build();

        $this->recipeRepository
            ->method('get')
            ->willThrowException(new \RuntimeException('Recipe not found'));

        $this->updater->expects($this->never())->method('update');
        $this->recipeRepository->expects($this->never())->method('save');

        $this->expectException(\RuntimeException::class);

        ($this->handler)($command);
    }
}