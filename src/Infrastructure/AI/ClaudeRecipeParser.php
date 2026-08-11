<?php

namespace App\Infrastructure\AI;

use Anthropic\Client;
use App\Application\Recipe\DTO\ImportedRecipeDTO;
use App\Infrastructure\Import\Parser\RecipeParserInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class ClaudeRecipeParser implements RecipeParserInterface
{
    public function __construct(private ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function parse(
        string $content,
        string $sourceUrl,
        string $imageUrl,
    ): ImportedRecipeDTO {
        // Implement the parsing logic here using Claude AI API
        // For now, we'll return a dummy ImportedRecipeDTO for demonstration purposes

        $client = new Client(
            apiKey: $this->parameterBag->get('ANTHROPIC_API_KEY') ?? 'my-anthropic-api-key',
        );

        $message = $client->messages->create(
            maxTokens: 1024,
            messages: [
                [
                    'role' => 'user',
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
                ]],
            model: 'claude-opus-4-8',
        );

        return new ImportedRecipeDTO(
            title: 'Dummy Recipe Title',
            description: 'Dummy Recipe Description',
            sourceUrl: $sourceUrl,
            imageUrl: $imageUrl,
            ingredients: [],
            steps: []
        );
    }
}
