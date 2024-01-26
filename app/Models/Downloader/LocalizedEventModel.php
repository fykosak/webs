<?php

declare(strict_types=1);

namespace App\Models\Downloader;

class LocalizedEventModel
{
    public int $eventId;
    public ?int $year;
    public ?int $eventYear;
    public ?string $begin;
    public ?string $end;
    public ?string $registrationBegin;
    public ?string $registrationEnd;
    public ?string $report;
    public ?string $description;
    public ?string $name;
    public int $eventTypeId;
    public ?string $place;
    public ?int $contestId;
}
