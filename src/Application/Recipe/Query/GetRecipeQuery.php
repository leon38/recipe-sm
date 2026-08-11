<?php

namespace App\Application\Recipe\Query;

use App\Domain\Recipe\ValueObject\ValueId;

final readonly class GetRecipeQuery
{
    public function __construct(
        public ValueId $id,
    ) {
    }
}
