<?php

declare(strict_types=1);

namespace App\Tests\Application\Recipe\CommandHandler;

use App\Application\Recipe\CommandHandler\SaveRecipeHandler;
use App\Application\Recipe\Factory\RecipeFactory;
use App\Tests\ApplicationTestCase;
use App\Tests\Builder\RecipeBuilder;
use App\Tests\Builder\SaveRecipeCommandBuilder;
use PHPUnit\Framework\MockObject\MockObject;

final class SaveRecipeHandlerTest extends ApplicationTestCase
{
    private RecipeFactory|MockObject $factory;
    private SaveRecipeHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = $this->createMock(RecipeFactory::class);
        $this->handler = new SaveRecipeHandler($this->factory, $this->recipeRepository);
    }

    public function testItCreatesRecipeFromCommandAndSavesIt(): void
    {
        $command = SaveRecipeCommandBuilder::create()
            ->withTitle('Tarte aux pommes')
            ->build();

        $recipe = RecipeBuilder::create()
            ->withTitle('Tarte aux pommes')
            ->build();

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($command)
            ->willReturn($recipe);

        $this->recipeRepository
            ->expects($this->once())
            ->method('save')
            ->with($recipe);

        $result = ($this->handler)($command);

        self::assertSame($recipe, $result);
    }

    public function testItDoesNotSaveWhenFactoryFails(): void
    {
        $command = SaveRecipeCommandBuilder::create()->build();

        $this->factory
            ->method('create')
            ->willThrowException(new \InvalidArgumentException('Nom de recette invalide'));

        $this->recipeRepository->expects($this->never())->method('save');

        $this->expectException(\InvalidArgumentException::class);

        ($this->handler)($command);
    }
}