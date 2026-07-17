<?php
namespace App\Domain\Recipe\Repository;

use App\Domain\Recipe\Entity\Ingredient;
use App\Domain\Recipe\ValueObject\ValueId;
use Doctrine\DBAL\LockMode;

interface IngredientRepositoryInterface
{
    public function save(Ingredient $ingredient): void;

    public function get(ValueId $valueId): Ingredient;

    public function find(mixed $id, LockMode|int|null $lockMode = null, ?int $lockVersion = null): ?Ingredient;

    public function findOneByNormalizedName(string $normalizedName): ?Ingredient;
}