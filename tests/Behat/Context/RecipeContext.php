<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Step\Then;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

final class RecipeContext implements Context
{
    public function __construct(
        private readonly KernelBrowser $client,
    ) {
    }

    #[Then('the recipe title should be :title')]
    public function theRecipeTitleShouldBe(
        string $title,
    ): void {
        $data = $this->getJsonResponse();

        if (!isset($data['title'])) {
            throw new \RuntimeException('The response does not contain a "title" property.');
        }

        if ($data['title'] !== $title) {
            throw new \RuntimeException(sprintf('Expected recipe title "%s", got "%s".', $title, $data['title']));
        }
    }

    #[Then('the recipe should have :count ingredients')]
    public function theRecipeShouldHaveIngredients(
        int $count,
    ): void {
        $data = $this->getJsonResponse();

        if (!isset($data['ingredients'])) {
            throw new \RuntimeException('The response does not contain an "ingredients" property.');
        }

        if (!is_array($data['ingredients'])) {
            throw new \RuntimeException('The "ingredients" property is not an array.');
        }

        $actual = count($data['ingredients']);

        if ($actual !== $count) {
            throw new \RuntimeException(sprintf('Expected %d ingredients, got %d.', $count, $actual));
        }
    }

    #[Then('the recipe should have :count steps')]
    public function theRecipeShouldHaveSteps(
        int $count,
    ): void {
        $data = $this->getJsonResponse();

        if (!isset($data['steps'])) {
            throw new \RuntimeException('The response does not contain a "steps" property.');
        }

        if (!is_array($data['steps'])) {
            throw new \RuntimeException('The "steps" property is not an array.');
        }

        $actual = count($data['steps']);

        if ($actual !== $count) {
            throw new \RuntimeException(sprintf('Expected %d steps, got %d.', $count, $actual));
        }
    }

    #[Then('the recipe should have :count tags')]
    public function theRecipeShouldHaveTags(
        int $count,
    ): void {
        $data = $this->getJsonResponse();

        if (!isset($data['tags'])) {
            throw new \RuntimeException('The response does not contain a "tags" property.');
        }

        if (!is_array($data['tags'])) {
            throw new \RuntimeException('The "tags" property is not an array.');
        }

        $actual = count($data['tags']);

        if ($actual !== $count) {
            throw new \RuntimeException(sprintf('Expected %d tags, got %d.', $count, $actual));
        }
    }

    #[Then('the recipe should have :count categories')]
    public function theRecipeShouldHaveCategories(
        int $count,
    ): void {
        $data = $this->getJsonResponse();

        if (!isset($data['categories'])) {
            throw new \RuntimeException('The response does not contain a "categories" property.');
        }

        if (!is_array($data['categories'])) {
            throw new \RuntimeException('The "categories" property is not an array.');
        }

        $actual = count($data['categories']);

        if ($actual !== $count) {
            throw new \RuntimeException(sprintf('Expected %d categories, got %d.', $count, $actual));
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function getJsonResponse(): array
    {
        $content = $this->client->getResponse()->getContent();

        if (false === $content) {
            throw new \RuntimeException('Unable to retrieve response content.');
        }

        try {
            $data = json_decode(
                $content,
                true,
                512,
                JSON_THROW_ON_ERROR,
            );
        } catch (\JsonException $exception) {
            throw new \RuntimeException('The response is not valid JSON.', previous: $exception);
        }

        if (!is_array($data)) {
            throw new \RuntimeException('Expected the response to be a JSON object.');
        }

        return $data;
    }
}
