<?php
namespace App\UI\Http\Controller;


use App\Application\Recipe\Command\UpdateRecipeCommand;
use App\Application\Recipe\CommandHandler\UpdateRecipeHandler;
use App\UI\Http\Request\UpdateRecipeRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/recipe')]
final class UpdateRecipeController extends AbstractController
{

    public function __construct(private UpdateRecipeHandler $handler)
    {}

    #[Route(methods: ['PUT'])]
    public function __invoke(#[MapRequestPayload] UpdateRecipeRequest $dto): JsonResponse
    {
        $recipe = ($this->handler)(
            new UpdateRecipeCommand(
                $dto->id,
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