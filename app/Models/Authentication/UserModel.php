<?php

declare(strict_types=1);

namespace App\Models\Authentication;

use Nette\Security\IIdentity;

class UserModel implements IIdentity
{
    public function __construct(readonly public int $id,
                                readonly public string $name,
                                readonly public array $groups)
    {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return [];
    }
}