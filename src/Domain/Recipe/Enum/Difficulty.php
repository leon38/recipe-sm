<?php

namespace App\Domain\Recipe\Enum;

enum Difficulty: string
{
    case EASY = 'Facile';
    case MEDIUM = 'Moyen';
    case HARD = 'Difficile';
}
