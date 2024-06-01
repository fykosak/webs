<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Models;

use App\Models\Downloader\EventOrganizerModel;
use App\Models\Downloader\EventParticipantModel;

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
    public ?string $report;
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
    public ?array $schedule;
    /**
     * @var EventParticipantModel[]
     */
    public ?array $participants;
    /**
     * @var EventOrganizerModel[]
     */
    public ?array $organizers;
}
