<?php

declare(strict_types=1);

namespace Tests\Util;

use App\Domain\Recipe\Entity\Recipe;
use PHPUnit\Framework\Assert;

final class AssertRecipe
{
    public static function hasIngredient(Recipe $recipe, string $name): void
    {
        Assert::assertTrue(
            self::containsName($recipe->getIngredients(), $name),
            sprintf('La recette n\'a pas d\'ingrédient nommé "%s".', $name)
        );
    }

    public static function doesNotHaveIngredient(Recipe $recipe, string $name): void
    {
        Assert::assertFalse(
            self::containsName($recipe->getIngredients(), $name),
            sprintf('La recette a un ingrédient nommé "%s" alors qu\'elle ne devrait pas.', $name)
        );
    }

    public static function hasTag(Recipe $recipe, string $name): void
    {
        Assert::assertTrue(
            self::containsName($recipe->getTags(), $name),
            sprintf('La recette n\'a pas de tag nommé "%s".', $name)
        );
    }

    public static function doesNotHaveTag(Recipe $recipe, string $name): void
    {
        Assert::assertFalse(
            self::containsName($recipe->getTags(), $name),
            sprintf('La recette a un tag nommé "%s" alors qu\'elle ne devrait pas.', $name)
        );
    }

    public static function hasCategory(Recipe $recipe, string $name): void
    {
        Assert::assertTrue(
            self::containsName($recipe->getCategories(), $name),
            sprintf('La recette n\'a pas de catégorie nommée "%s".', $name)
        );
    }

    public static function doesNotHaveCategory(Recipe $recipe, string $name): void
    {
        Assert::assertFalse(
            self::containsName($recipe->getCategories(), $name),
            sprintf('La recette a une catégorie nommée "%s" alors qu\'elle ne devrait pas.', $name)
        );
    }

    public static function hasIngredientCount(Recipe $recipe, int $count): void
    {
        Assert::assertCount($count, $recipe->getIngredients());
    }

    /**
     * @param iterable<object{getName: callable(): string}> $collection
     */
    private static function containsName(iterable $collection, string $name): bool
    {
        foreach ($collection as $item) {
            if (self::normalize($item->getName()) === self::normalize($name)) {
                return true;
            }
        }

        return false;
    }

    private static function normalize(string $value): string
    {
        return mb_strtolower(trim($value));
    }
}
