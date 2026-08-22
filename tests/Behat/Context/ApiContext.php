<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

final class ApiContext implements Context
{
    private ?Response $response = null;

    private string $token = '';

    public function __construct(
        private readonly KernelBrowser $client,
    ) {
    }

    #[When('I send a :method request to :url with the following JSON:')]
    public function iSendARequestWithJson(
        string $method,
        string $url,
        string $json,
    ): void {
        $this->client->request(
            method: $method,
            uri: $url,
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            content: $json,
        );

        $this->response = $this->client->getResponse();
    }

    #[Then('the response status code should be :statusCode')]
    public function theResponseStatusCodeShouldBe(
        int $statusCode,
    ): void {
        $response = $this->getResponse();

        if ($statusCode !== $response->getStatusCode()) {
            throw new \RuntimeException(sprintf("Expected HTTP status %d but got %d.\n\nResponse:\n%s", $statusCode, $response->getStatusCode(), $response->getContent()));
        }
    }

    #[Then('the response should contain :value')]
    public function theResponseShouldContain(
        string $value,
    ): void {
        $content = $this->getResponse()->getContent();

        if (false === $content || !str_contains($content, $value)) {
            throw new \RuntimeException(sprintf('The response does not contain "%s".%s%s', $value, PHP_EOL, $content));
        }
    }

    #[Then('the response should be valid JSON')]
    public function theResponseShouldBeValidJson(): void
    {
        $content = $this->getResponse()->getContent();

        if (false === $content) {
            throw new \RuntimeException('The response does not contain valid JSON.');
        }

        json_decode($content, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(sprintf('Response is not valid JSON: %s', json_last_error_msg()));
        }
    }

    #[Then('the response JSON property :property should equal :expected')]
    public function theResponseJsonPropertyShouldEqual(
        string $property,
        string $expected,
    ): void {
        $data = $this->getJsonResponse();

        if (!array_key_exists($property, $data)) {
            throw new \RuntimeException(sprintf('Property "%s" does not exist in the response.', $property));
        }

        if ((string) $data[$property] !== $expected) {
            throw new \RuntimeException(sprintf('Expected "%s" for "%s", got "%s".', $expected, $property, (string) $data[$property]));
        }
    }

    #[Given('I am authenticated as :email with password :password')]
    #[Given('I am authenticated as :email')]
    public function iAmAuthenticatedAs(
        string $email,
        string $password = 'password',
    ): void {
        $response = $this->client->request(
            method: 'POST',
            uri: '/api/login_check',
            server: [
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode([
                'email' => $email,
                'password' => $password,
            ]),
        );

        $response = $this->client->getResponse();

        $data = json_decode($response->getContent(), true);

        $this->token = $data['token'];
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $this->token));
    }

    private function getResponse(): Response
    {
        if (null === $this->response) {
            throw new \RuntimeException('No HTTP response is available.');
        }

        return $this->response;
    }

    /**
     * @return array<string, mixed>
     */
    private function getJsonResponse(): array
    {
        $content = $this->getResponse()->getContent();

        if (false === $content) {
            throw new \RuntimeException('The response does not contain valid JSON.');
        }

        $data = json_decode(
            $this->getResponse()->getContent(),
            true,
        );

        if (!is_array($data)) {
            throw new \RuntimeException('The response does not contain a JSON object.');
        }

        return $data;
    }
}
