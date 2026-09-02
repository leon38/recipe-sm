<?php

namespace App\Application\User\CommandHandler;

use App\Application\User\Command\RegisterUserCommand;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class RegisterUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(RegisterUserCommand $command): void
    {
        if (null !== $this->userRepository->findByEmail($command->email)) {
            throw new \DomainException('A user with this email already exists.');
        }

        $user = User::create(
            email: $command->email,
            password: $command->password
        );

        $user->changePassword(
            $this->passwordHasher->hashPassword(
                $user,
                $command->password,
            ),
        );

        $this->userRepository->save($user);
    }
}
