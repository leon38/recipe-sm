<?php

declare(strict_types=1);

namespace App\UI\Http\Controller;

use App\Application\Recipe\Query\GetRecipeQuery;
use App\Application\Shared\Bus\QueryBusInterface;
use App\Domain\Recipe\ValueObject\ValueId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/public/recipe')]
final class SingleRecipeController extends AbstractController
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    #[Route('/{id}', methods: ['GET'])]
    public function __invoke(string $id): JsonResponse
    {
        return $this->json(
            $this->queryBus->ask(
                new GetRecipeQuery(
                    ValueId::fromString($id),
                ),
            ),
        );
    }
}
