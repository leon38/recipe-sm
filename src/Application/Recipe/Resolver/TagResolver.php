<?php
namespace App\Application\Recipe\Resolver;

use App\Domain\Recipe\Entity\Tag;
use App\Domain\Recipe\Repository\TagRepositoryInterface;

final class TagResolver
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository,
    ) {
    }

    /**
     * @param array<array<string, string>> $tags
     * @return Tag[]
     */
    public function resolve(array $tags): array
    {
        $existingTags = $this->tagRepository->findBy(
            ['name' => array_column($tags, 'name')],
        );

        $tagNames = array_map(fn(Tag $tag) => $tag->getName(), $existingTags);

        foreach($tags as $tagData) {
            if (!in_array($tagData['name'], $tagNames)) {
                $existingTags[] = new Tag(
                    name: $tagData['name'],
                );
            }
        }
        return $existingTags;
    }
}