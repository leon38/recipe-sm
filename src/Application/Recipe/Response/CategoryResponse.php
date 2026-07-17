<?php
namespace App\Application\Recipe\Response;

final class CategoryResponse implements \JsonSerializable
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}