<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Domain\Recipe\ValueObject\ValueId;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserContext implements Context
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    #[Given('I have a user :email with password :password')]
    public function iHaveAUserWithPassword(
        string $email,
        string $password,
    ): void {
        $user = $this->userRepository->findByEmail($email);

        if (null !== $user) {
            return;
        }

        $user = new User(
            id: ValueId::generate(),
            email: $email,
            password: '',
        );

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password,
        );

        $user->changePassword($hashedPassword);
        $this->userRepository->save($user);
    }
}
