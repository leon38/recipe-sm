<?php
namespace App\Domain\Recipe\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Domain\Recipe\Entity\Tag;

/**
 * @extends ServiceEntityRepository<Tag>
 */
final class DoctrineTagRepository extends ServiceEntityRepository implements TagRepositoryInterface
{
    public function __construct(
        private readonly \Doctrine\Persistence\ManagerRegistry $doctrine,
    ) {
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, mixed>|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array<\App\Domain\Recipe\Entity\Tag>
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->doctrine->getRepository(Tag::class)->findBy($criteria, $orderBy, $limit, $offset);
    }
}