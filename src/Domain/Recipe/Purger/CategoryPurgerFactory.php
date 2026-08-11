<?php
namespace App\Domain\Recipe\Purger;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Purger\PurgerFactory;
use Doctrine\Common\DataFixtures\Purger\PurgerInterface;

/**
 * @implements PurgerFactory<CategoryPurger>
 */
final class CategoryPurgerFactory implements PurgerFactory
{
    public function createForEntityManager(?string $emName, EntityManagerInterface $em, array $excluded = [], bool $purgeWithTruncate = false) : PurgerInterface
    {
        return new CategoryPurger();
    }

}