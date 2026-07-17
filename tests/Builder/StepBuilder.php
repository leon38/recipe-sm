<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Recipe\Entity\Step;
use App\Domain\Recipe\ValueObject\ValueId;

final class StepBuilder
{
    private ValueId $id;

    private int $position = 1;

    private string $instruction = 'Ajouter le sucre, la farine et la levure';


    private function __construct()
    {
        $this->id = ValueId::generate();
    }

    public static function create(): self
    {
        return new self();
    }

    public function withId(?ValueId $id = null): self
    {
        $this->id = $id ?? ValueId::generate();

        return $this;
    }

    public function withPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function withInstruction(string $instruction): self
    {
        $this->instruction = $instruction;

        return $this;
    }


    public function build(): Step
    {
        return new Step(
            id: $this->id,
            position: $this->position,
            instruction: $this->instruction
        );
    }
}