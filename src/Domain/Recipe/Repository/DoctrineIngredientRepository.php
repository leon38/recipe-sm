<?php
namespace App\Domain\Recipe\Repository;

use App\Domain\Recipe\Entity\Ingredient;
use App\Domain\Recipe\Repository\IngredientRepositoryInterface;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Domain\Recipe\ValueObject\ValueId;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
final class DoctrineIngredientRepository extends ServiceEntityRepository implements IngredientRepositoryInterface
{
    public function __construct(private ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    public function save(Ingredient $ingredient): void
    {
        
        $this->registry->getManager()->persist($ingredient);
        $this->registry->getManager()->flush();
    }

    public function get(ValueId $valueId): Ingredient
    {
        return parent::find($valueId->getValue());
    }

    /**
     * @param ValueId $id
     * @param LockMode|int|null $lockMode
     * @param int|null $lockVersion
     * @return Ingredient|null
     */
    public function find(mixed $id, LockMode|int|null $lockMode = null, int|null $lockVersion = null): ?Ingredient
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    public function findOneByNormalizedName(string $normalizedName): ?Ingredient
    {
        return $this->createQueryBuilder('i')
            ->where('i.normalizedName = :normalizedName')
            ->setParameter('normalizedName', $normalizedName)
            ->getQuery()
            ->getOneOrNullResult();
    }
}