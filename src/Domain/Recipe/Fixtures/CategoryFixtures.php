<?php

namespace App\Domain\Recipe\Fixtures;

use App\Domain\Recipe\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CategoryFixtures extends Fixture
{
    public const CATEGORY_DESSERT = 'dessert';
    public const CATEGORY_MAIN_COURSE = 'Plat principal';
    public const CATEGORY_FIRST_COURSE = 'Entrée';
    public const CATEGORY_APPETIZER = 'Apéritif';

    public function load(ObjectManager $manager): void
    {
        // create 20 products with random prices
        $categories = [
            self::CATEGORY_DESSERT,
            self::CATEGORY_MAIN_COURSE,
            self::CATEGORY_FIRST_COURSE,
            self::CATEGORY_APPETIZER,
        ];
        foreach ($categories as $categoryName) {
            $category = Category::create($categoryName);
            $manager->persist($category);
        }
        $manager->flush();
    }
}
