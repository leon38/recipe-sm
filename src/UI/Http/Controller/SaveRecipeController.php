<?php

namespace App\UI\Http\Controller;

use App\Application\Recipe\Command\SaveRecipeCommand;
use App\Application\Recipe\CommandHandler\SaveRecipeHandler;
use App\Domain\Recipe\ValueObject\ValueId;
use App\UI\Http\Request\ParsedRecipeRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/recipe')]
final class SaveRecipeController extends AbstractController
{
    public function __construct(private SaveRecipeHandler $handler)
    {
    }

    #[Route(methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] ParsedRecipeRequest $dto): JsonResponse
    {
        $recipe = ($this->handler)(
            new SaveRecipeCommand(
                userId: ValueId::fromString($this->getUser()->getUserIdentifier()),
                title: $dto->title,
                description: $dto->description,
                prepTime: $dto->prepTime,
                cookTime: $dto->cookTime,
                difficulty: $dto->difficulty,
                servings: $dto->servings,
                season: $dto->season,
                imageUrl: $dto->imageUrl,
                sourceUrl: $dto->sourceUrl,
                ingredients: $dto->ingredients,
                steps: $dto->steps,
                tags: $dto->tags,
                categories: $dto->categories
            )
        );

        return $this->json($recipe, 200);
    }
}
