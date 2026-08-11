<?php
namespace App\Infrastructure\AI;

use App\Application\Recipe\DTO\ImportedRecipeDTO;
use App\Infrastructure\Import\Parser\RecipeParserInterface;
use Gemini;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class GeminiRecipeParser implements RecipeParserInterface
{

    public function __construct(private ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function parse(
        string $content,
        string $sourceUrl,
        ?string $imageUrl = null
    ): ImportedRecipeDTO {
        // Implement the parsing logic here using Gemini API
        // For now, we'll return a dummy ImportedRecipeDTO for demonstration purposes

        $apiKey = $this->parameterBag->get('GEMINI_API_KEY') ?? 'your-gemini-api-key';
        $client = Gemini::client($apiKey);

        $result = $client->generativeModel(model: 'gemini-2.0-flash')->generateContent(
            <<<PROMPT
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
                        PROMPT
        );
        
        $parsedData = json_decode($result->text(), true);

        return new ImportedRecipeDTO(
            title: $parsedData['title'] ?? 'Default Title',
            description: $parsedData['description'] ?? null,
            sourceUrl: $sourceUrl,
            imageUrl: $imageUrl ?? '',
            ingredients: $parsedData['ingredients'] ?? [],
            steps: $parsedData['steps'] ?? []
        );
    }
}