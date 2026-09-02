<?php

namespace App\UI\Http\Controller;

use App\Application\User\Command\RegisterUserCommand;
use App\Application\User\CommandHandler\RegisterUserHandler;
use App\UI\Http\Request\RegisterRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/register', methods: ['POST'])]
final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly RegisterUserHandler $handler,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] RegisterRequest $request,
    ): JsonResponse {
        ($this->handler)(
            new RegisterUserCommand(
                email: $request->email,
                password: $request->password,
            ),
        );

        return $this->json(
            ['message' => 'User registered successfully.'],
            Response::HTTP_CREATED,
        );
    }
}
