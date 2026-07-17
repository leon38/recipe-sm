<?php
namespace App\Domain\Recipe\Repository;

interface TagRepositoryInterface
{
    public function findBy(array $criteria): array;
}