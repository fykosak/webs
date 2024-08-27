<?php

declare(strict_types=1);

namespace App\Models\Downloader;

class EventParticipantModel
{
    public string $name;
    public int $personId;
    public int $eventParticipantId;
    public string $status;
    public int $lunchCount;
    public ?string $code;
    public ?object $school;
    public ?string $studyYear;
}
