<?php

namespace App\Domain\Recipe\Enum;

enum Season: string
{
    case SPRING = 'printemps';
    case SUMMER = 'ete';
    case AUTUMN = 'automne';
    case WINTER = 'hiver';
    case ALL_SEASONS = 'toutes saisons';
}
