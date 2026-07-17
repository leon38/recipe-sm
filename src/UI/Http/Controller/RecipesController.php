<?php
namespace App\UI\Http\Controller;

use App\Application\Recipe\Query\SearchRecipesQuery;
use App\Application\Recipe\Response\PaginatedResponse;
use App\Infrastructure\QueryBus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/recipe')]
final class RecipesController extends AbstractController
{

    public function __construct(private QueryBusInterface $queryBus)
    {}

    #[Route("/", methods: ["GET"])]
    public function __invoke(Request $request): JsonResponse
    {
        $query = new SearchRecipesQuery(
            page: max(1, $request->query->getInt('page', 1)),
            perPage: min(
                100,
                max(1, $request->query->getInt('perPage', 20))
            ),
        );

        /** @var PaginatedResponse $response */
        $response = $this->queryBus->ask($query);

        return $this->json($response);
    }
}