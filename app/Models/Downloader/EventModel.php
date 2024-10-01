<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use Fykosak\Utils\DateTime\Period;

final class EventModel
{
    public readonly int $eventId;
    public readonly int $eventTypeId;
    public readonly string $name;
    public readonly int $eventYear;
    public readonly int $year;
    public readonly \DateTimeImmutable $begin;
    public readonly \DateTimeImmutable $end;
    public readonly \DateTimeImmutable $registrationBegin;
    public readonly \DateTimeImmutable $registrationEnd;
    public readonly ?string $report;
    /**
     * @var string[] $reportNew
     */
    public readonly array $reportNew;
    /**
     * @var string[] $description
     */
    public readonly array $description;
    /**
     * @var string[] $nameNew
     */
    public readonly array $nameNew;
    public readonly ?string $place;
    public readonly ?int $contestId;
    public readonly ?array $schedule;


    public readonly GameModel|null $game;
    public readonly Period $registration; // TODO magic?

    public function getRegistrationPeriod(): Period
    {
        return new Period($this->registrationBegin, $this->registrationEnd);
    }

    public function getEventPeriod(): Period
    {
        return new Period($this->begin, $this->end);
    }

    public function getGamePeriod(): ?Period
    {
        return $this->game?->getGamePeriod();
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

    public function getYear(): int
    {
        return (int)$this->begin->format('Y');
    }

    public function getMonth(): string
    {
        return $this->begin->format('m');
    }
}
