<?php
namespace App\Domain\Recipe\Repository;

interface TagRepositoryInterface
{
    /**
     * @param array<string, mixed> $criteria
     * @param array<string, mixed>|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array<\App\Domain\Recipe\Entity\Tag>
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;
}