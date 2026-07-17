<?php

namespace App\Domain\Recipe\Repository;

use App\Domain\Recipe\Entity\Recipe;
use App\Domain\Recipe\ValueObject\ValueId;
use Doctrine\DBAL\LockMode;


interface RecipeRepositoryInterface
{
    public function save(Recipe $recipe): void;

    public function get(ValueId $valueId): Recipe;

    public function find(mixed $id, LockMode|int|null $lockMode = null, ?int $lockVersion = null): ?Recipe;
}