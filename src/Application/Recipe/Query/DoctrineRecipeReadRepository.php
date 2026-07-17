<?php
namespace App\Application\Recipe\Query;

use App\Application\Recipe\Response\PaginatedResponse;
use App\Domain\Recipe\Entity\Recipe;
use App\Application\Recipe\Mapper\RecipeResponseMapper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


final class DoctrineRecipeReadRepository extends ServiceEntityRepository implements RecipeReadRepositoryInterface
{
    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly RecipeResponseMapper $recipeResponseMapper,
    ) {
        parent::__construct($registry, Recipe::class);
    }

    public function search(SearchRecipesQuery $query): PaginatedResponse
    {
        $qb = $this->createQueryBuilder('r');

        $total = (clone $qb)
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();

            dump($query->offset());

        $recipes = $qb
            ->setFirstResult($query->offset())
            ->setMaxResults($query->perPage)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        return new PaginatedResponse(
            items: array_map(fn(Recipe $recipe): array => $this->recipeResponseMapper->map($recipe)->jsonSerialize(), $recipes),
            page: $query->page,
            perPage: $query->perPage,
            total: $total
        );

    }
}