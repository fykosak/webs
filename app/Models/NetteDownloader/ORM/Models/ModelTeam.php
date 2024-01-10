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
    public string $status;
    public int $teamId;

    /**
     * @var ModelMember[]
     */
    public $members;

    /*
     * @var ModelPerson[]
     */
    public $teachers;
}
