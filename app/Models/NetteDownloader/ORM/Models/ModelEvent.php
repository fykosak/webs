<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Models;

use Fykosak\Utils\DateTime\Period;

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

    public function getRegistrationPeriod(): Period
    {
        return new Period($this->registrationBegin, $this->registrationEnd);
    }

    public function getEventPeriod(): Period
    {
        return new Period($this->begin, $this->end);
    }
}
