<?php
namespace App\UI\Http\Controller;


use App\Application\Recipe\Command\SaveRecipeCommand;
use App\Application\Recipe\CommandHandler\SaveRecipeHandler;
use App\UI\Http\Request\ParsedRecipeRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/recipe')]
final class SaveRecipeController extends AbstractController
{

    public function __construct(private SaveRecipeHandler $handler)
    {}

    #[Route(methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] ParsedRecipeRequest $dto): JsonResponse
    {
        $recipe = ($this->handler)(
            new SaveRecipeCommand(
                $dto->title,
                $dto->description,
                $dto->prepTime,
                $dto->cookTime,
                $dto->difficulty,
                $dto->servings,
                $dto->season,
                $dto->imageUrl,
                $dto->sourceUrl,        
                $dto->ingredients,
                $dto->steps,
                $dto->tags,
                $dto->categories
            )
        );

        return $this->json($recipe, 200);
    }
}