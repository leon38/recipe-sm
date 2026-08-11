<?php

namespace App\Infrastructure\AI;

use App\Application\Recipe\DTO\ImportedIngredientDTO;
use App\Application\Recipe\DTO\ImportedRecipeDTO;
use App\Application\Recipe\DTO\ImportedStepDTO;
use App\Infrastructure\Import\Parser\RecipeParserInterface;
use OpenAI\Client;

final readonly class OpenAiRecipeParser implements RecipeParserInterface
{
    public function __construct(
        private Client $client,
    ) {
    }

    public function parse(
        string $content,
        string $sourceUrl,
        string $imageUrl,
    ): ImportedRecipeDTO {
        $response = $this->client->chat()->create([
            'model' => 'gpt-4.1-mini',
            'temperature' => 0,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => <<<PROMPT
You are a recipe extraction engine.

Extract:
- title
- ingredients
- preparation steps

Return ONLY valid JSON.

Schema:

{
  "title": "string",
  "ingredients": [
    {
      "name": "string",
      "quantity": number|null,
      "unit": "string|null"
    }
  ],
  "steps": [
    "string"
  ]
}
PROMPT,
                ],
                [
                    'role' => 'user',
                    'content' => $content,
                ],
            ],
            'response_format' => [
                'type' => 'json_object',
            ],
        ]);

        $json = json_decode(
            $response->choices[0]->message->content,
            true,
            flags: JSON_THROW_ON_ERROR
        );

        $ingredients = [];

        foreach ($json['ingredients'] ?? [] as $ingredient) {
            $ingredients[] = new ImportedIngredientDTO(
                name: $ingredient['name'],
                quantity: $ingredient['quantity'],
                unit: $ingredient['unit'],
            );
        }

        $steps = [];

        foreach ($json['steps'] ?? [] as $step) {
            $steps[] = new ImportedStepDTO(
                instruction: $step,
            );
        }

        return new ImportedRecipeDTO(
            title: mb_substr(
                $json['title'] ?? 'Sans titre',
                0,
                255
            ),
            description: $content,
            sourceUrl: $sourceUrl,
            imageUrl: $imageUrl,
            ingredients: $ingredients,
            steps: $steps,
        );
    }
}
