<?php

namespace App\Application\Recipe\Response;

final readonly class PaginatedResponse implements \JsonSerializable
{
    /**
     * @param array<mixed> $items
     */
    public function __construct(
        public array $items,
        public int $page,
        public int $perPage,
        public int $total,
    ) {
    }

    public function pages(): int
    {
        return (int) ceil($this->total / $this->perPage);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'items' => $this->items,
            'pagination' => [
                'page' => $this->page,
                'perPage' => $this->perPage,
                'total' => $this->total,
                'pages' => $this->pages(),
            ],
        ];
    }
}
