<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Models;

class ModelScheduleItem
{
    public int $scheduleItemId;
    public int $scheduleGroupId;

    public ?int $totalCapacity;
    public ?int $usedCapacity;

    public ?bool $requireIdNumber;

    /**
     * @var string[]|null
     */
    public ?array $label;

    /**
     * @var string[]|null
     */
    public ?array $description;
    public ?array $price;
}
