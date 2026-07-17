<?php
namespace App\Domain\Recipe\Enum;

enum Unit: string
{
    case GRAM = 'g';
    case KILOGRAM = 'kg';
    case MILLILITER = 'ml';
    case CENTILITER = 'cl';
    case LITER = 'l';

    case TABLESPOON = 'cuillère à soupe';
    case TEASPOON = 'cuillère à café';

    case PINCH = 'pincée';
    case HANDFUL = 'poignée';
}