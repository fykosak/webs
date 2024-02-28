<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use App\Models\Downloader\ModelPersonSchedule;
use App\Models\Downloader\EventParticipantModel;

class EventDetailModel
{
    public int $eventId;
    public int $eventTypeId;
    public int $eventYear;
    public int $year;
    public \DateTimeImmutable $begin;
    public \DateTimeImmutable $end;
    public \DateTimeImmutable $registrationBegin;
    public \DateTimeImmutable $registrationEnd;
    public ?string $report;
    /**
     * @var string[] $reportNew
     */
    public array $reportNew;
    /**
     * @var string[] $description
     */
    public array $description;
    public string $name;
    /**
     * @var string[] $nameNew
     */
    public array $nameNew;
    public ?string $place;
    public ?int $contestId;

    public ?array $schedule;
    /**
     * @var EventParticipantModel[]
     */
    public ?array $participants;
}
