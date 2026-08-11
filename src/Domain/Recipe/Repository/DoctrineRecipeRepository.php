<?php

namespace App\Domain\Recipe\Repository;

use App\Domain\Recipe\Entity\Recipe;
use App\Domain\Recipe\ValueObject\ValueId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
final class DoctrineRecipeRepository extends ServiceEntityRepository implements RecipeRepositoryInterface
{
    public function __construct(private ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function save(Recipe $recipe): void
    {
        $this->registry->getManager()->persist($recipe);
        $this->registry->getManager()->flush();
    }

    public function get(ValueId $valueId): Recipe
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.ingredients', 'i')
            ->addSelect('i')
            ->leftJoin('r.steps', 's')
            ->addSelect('s')
            ->leftJoin('r.tags', 't')
            ->addSelect('t')
            ->leftJoin('r.categories', 'c')
            ->addSelect('c')
            ->where('r.id = :id')
            ->setParameter('id', $valueId->getValue())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param ValueId $id
     */
    public function find(mixed $id, LockMode|int|null $lockMode = null, ?int $lockVersion = null): ?Recipe
    {
        return parent::find($id, $lockMode, $lockVersion);
    }
}
