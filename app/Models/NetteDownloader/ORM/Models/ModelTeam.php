<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Models;

class ModelTeam
{
    public string $category;
    public \DateTimeImmutable $created;
    public ?bool $forceA;
    public ?string $gameLang = null;
    public string $name;
    public ?string $password = null;
    public ?string $phone = null;
    public ?int $points = null;
    public ?int $rankCategory = null;
    public ?int $rankTotal = null;
    public string $state;
    public int $teamId;

    /**
     * @var ModelMember[]
     */
    public array $members;

    /**
     * @var ModelPerson[]
     */
    public array $teachers;
}
