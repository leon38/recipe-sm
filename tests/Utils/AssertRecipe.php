<?php

declare(strict_types=1);

namespace App\Tests\Utils;

use App\Domain\Recipe\Entity\Category;
use App\Domain\Recipe\Entity\Ingredient;
use App\Domain\Recipe\Entity\Recipe;
use App\Domain\Recipe\Entity\Tag;
use PHPUnit\Framework\Assert;

final class AssertRecipe
{
    public static function hasIngredient(Recipe $recipe, string $name): void
    {
        Assert::assertTrue(
            self::containsName($recipe->getIngredients(), $name, static fn (Ingredient $ingredient): string => $ingredient->getName()),
            sprintf('La recette n\'a pas d\'ingrédient nommé "%s".', $name)
        );
    }

    public static function doesNotHaveIngredient(Recipe $recipe, string $name): void
    {
        Assert::assertFalse(
            self::containsName($recipe->getIngredients(), $name, static fn (Ingredient $ingredient): string => $ingredient->getName()),
            sprintf('La recette a un ingrédient nommé "%s" alors qu\'elle ne devrait pas.', $name)
        );
    }

    public static function hasTag(Recipe $recipe, string $name): void
    {
        Assert::assertTrue(
            self::containsName($recipe->getTags(), $name, static fn (Tag $tag): string => $tag->getName()),
            sprintf('La recette n\'a pas de tag nommé "%s".', $name)
        );
    }

    public static function doesNotHaveTag(Recipe $recipe, string $name): void
    {
        Assert::assertFalse(
            self::containsName($recipe->getTags(), $name, static fn (Tag $tag): string => $tag->getName()),
            sprintf('La recette a un tag nommé "%s" alors qu\'elle ne devrait pas.', $name)
        );
    }

    public static function hasCategory(Recipe $recipe, string $name): void
    {
        Assert::assertTrue(
            self::containsName($recipe->getCategories(), $name, static fn (Category $category): string => $category->getName()),
            sprintf('La recette n\'a pas de catégorie nommée "%s".', $name)
        );
    }

    public static function doesNotHaveCategory(Recipe $recipe, string $name): void
    {
        Assert::assertFalse(
            self::containsName($recipe->getCategories(), $name, static fn (Category $category): string => $category->getName()),
            sprintf('La recette a une catégorie nommée "%s" alors qu\'elle ne devrait pas.', $name)
        );
    }

    public static function hasIngredientCount(Recipe $recipe, int $count): void
    {
        Assert::assertCount($count, $recipe->getIngredients());
    }

    /**
     * @template T of object
     *
     * @param iterable<T> $collection
     */
    private static function containsName(
        iterable $collection,
        string $name,
        callable $getName,
    ): bool {
        foreach ($collection as $item) {
            if (self::normalize($getName($item)) === self::normalize($name)) {
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
