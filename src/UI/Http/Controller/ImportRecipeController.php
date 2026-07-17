<?php

namespace App\UI\Http\Controller;

use App\Application\Recipe\Command\ImportRecipeFromUrlCommand;
use App\Application\Recipe\CommandHandler\ImportRecipeFromUrlHandler;
use App\UI\Http\Request\ImportRecipeRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/recipe')]
final class ImportRecipeController extends AbstractController
{
    public function __construct(
        private ImportRecipeFromUrlHandler $handler,
    ) {
    }

    #[Route('/import', name: 'recipe_import', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] ImportRecipeRequest $dto): JsonResponse
    {
        $recipe = ($this->handler)(
            new ImportRecipeFromUrlCommand(
                $dto->url,
            )
        );

        return $this->json($recipe, 200);
    }
}