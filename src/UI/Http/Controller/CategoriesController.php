<?php

namespace App\UI\Http\Controller;

use App\Application\Category\Query\GetCategoriesQuery;
use App\Application\Shared\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/public/category')]
final class CategoriesController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    #[Route(methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            $this->queryBus->ask(
                new GetCategoriesQuery(),
            ),
        );
    }
}
