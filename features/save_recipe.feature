Feature: Save a recipe

  As an API consumer
  I want to save a recipe
  So that it can be retrieved later

  Scenario: Save a recipe
    When I send a POST request to "/api/recipe" with the following JSON:
      """
      {
        "title": "Tarte aux pommes",
        "description": "Une délicieuse tarte aux pommes",
        "prepTime": 20,
        "cookTime": 35,
        "difficulty": "easy",
        "servings": 6,
        "season": "autumn",
        "imageUrl": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADElEQVQImWNgYGAAAAAEAAGjChXjAAAAAElFTkSuQmCC",
        "sourceUrl": "https://example.com/recipe",
        "ingredients": [],
        "steps": [],
        "tags": [],
        "categories": []
      }
      """
    Then the response status code should be 200
    And the response should be valid JSON
    And the response should contain "Tarte aux pommes"

  Scenario: Save a recipe without title
    When I send a POST request to "/api/recipe" with the following JSON:
      """
      {
        "description": "Une délicieuse tarte aux pommes",
        "prepTime": 20,
        "cookTime": 35,
        "difficulty": "easy",
        "servings": 6,
        "season": "autumn",
        "imageUrl": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADElEQVQImWNgYGAAAAAEAAGjChXjAAAAAElFTkSuQmCC",
        "sourceUrl": "https://example.com/recipe",
        "ingredients": [],
        "steps": [],
        "tags": [],
        "categories": []
      }
      """
    Then the response status code should be 422
    And the response should be valid JSON
    And the response should contain '"title":"Validation Failed"'
    And the response should contain "title: This value should be of type string."    