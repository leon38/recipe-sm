<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Recipe\ValueObject\ValueId;
use App\Domain\User\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findById(ValueId $id): ?User;

    public function findByEmail(string $email): ?User;

    public function remove(User $user): void;
}
