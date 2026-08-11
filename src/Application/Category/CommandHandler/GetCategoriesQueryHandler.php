<?php

namespace App\Application\Category\CommandHandler;

use App\Application\Category\Query\GetCategoriesQuery;
use App\Application\Category\Response\CategoryResponse;
use App\Domain\Recipe\Repository\CategoryRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final readonly class GetCategoriesQueryHandler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    /**
     * @return list<CategoryResponse>
     */
    public function __invoke(GetCategoriesQuery $query): array
    {
        return array_map(
            static fn ($category) => new CategoryResponse(
                id: (string) $category->getId(),
                name: $category->getName(),
            ),
            $this->categoryRepository->findAll(),
        );
    }
}
