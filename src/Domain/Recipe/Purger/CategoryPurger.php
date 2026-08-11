<?php

namespace App\Domain\Recipe\Purger;

use Doctrine\Common\DataFixtures\Purger\PurgerInterface;

class CategoryPurger implements PurgerInterface
{
    public function purge(): void
    {
    }
}
