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

    public function getGamePeriod(): Period
    {
        return $this->getEventPeriod(); // TODO!!!!
    }

    public function getNearEventPeriod(): Period
    {
        $begin = $this->begin->sub(new \DateInterval('P3D'));
        $end = $this->begin->add(new \DateInterval('P1D'));
        return new Period($begin, $end);
    }
    /**
     * Returns true about a week after the event when no one is interested in game already.
     * @throws \Throwable
     */
    public function isLongAfterTheEvent(): bool
    {
        $event = $this->end->add(new \DateInterval('P7D'));
        return new \DateTime() > $event;
    }
}
