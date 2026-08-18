<?php

namespace App\Infrastructure\Doctrine\Recipe;

use App\Domain\Recipe\Entity\Tag;
use App\Domain\Recipe\Repository\TagRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
final class DoctrineTagRepository extends ServiceEntityRepository implements TagRepositoryInterface
{
    public function __construct(private ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * @param array<string, mixed>      $criteria
     * @param array<string, mixed>|null $orderBy
     *
     * @return array<Tag>
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->registry->getManager()->getRepository(Tag::class)->findBy($criteria, $orderBy, $limit, $offset);
    }
}
