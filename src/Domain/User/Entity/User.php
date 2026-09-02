<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Recipe\ValueObject\ValueId;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity()]
#[ORM\Table(name: 'user')]
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @param string[] $roles
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'value_id', unique: true)]
        private readonly ValueId $id,
        #[ORM\Column(length: 180, unique: true)]
        private string $email,
        #[ORM\Column]
        private string $password,
        #[ORM\Column]
        private array $roles = ['ROLE_USER'],
    ) {
    }

    public static function create(
        string $email,
        string $password,
    ): self {
        return new self(
            id: ValueId::generate(),
            email: $email,
            password: $password,
        );
    }

    public function getId(): ValueId
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function changeEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Symfony utilise cette méthode comme identifiant de connexion.
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function changePassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return array_values(array_unique([
            ...$this->roles,
            'ROLE_USER',
        ]));
    }

    /**
     * @param string[] $roles
     */
    public function changeRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function eraseCredentials(): void
    {
    }
}
