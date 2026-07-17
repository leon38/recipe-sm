<?php
namespace App\Application\Recipe\Factory;

use App\Domain\Recipe\Entity\Step;

final readonly class StepFactory
{
    /**
     * @param array<int,array<string,mixed>> $steps
     * @return Step[]
     */
    public function createMany(array $steps): array
    {
        return array_map(
            fn(array $step, int $index) => Step::create(
                position: $index + 1,
                instruction: $step['instruction'],
            ),
            $steps,
            array_keys($steps),
        );
    }
}