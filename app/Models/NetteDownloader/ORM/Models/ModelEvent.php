<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Models;

class ModelEvent
{
    public int $eventId;
    public int $eventTypeId;
    public string $name;
    public int $eventYear;
    public int $year;
    public \DateTimeImmutable $begin;
    public \DateTimeImmutable $end;
    public \DateTimeImmutable $registrationBegin;
    public \DateTimeImmutable $registrationEnd;
    /**
     * @var string[] $reportNew
     */
    public array $reportNew;
    /**
     * @var string[] $description
     */
    public array $description;
    /**
     * @var string[] $nameNew
     */
    public array $nameNew;
    public ?string $place;
    public ?int $contestId;
}
