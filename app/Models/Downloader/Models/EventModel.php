<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models;

use Fykosak\Utils\DateTime\Period;

final readonly class EventModel
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
     * @var string[] $report
     */
    public array $report;
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


    public GameModel|null $game;
    /**
     * @var string[] $nameNew
     */
    public array $registration; // TODO cast to Period

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

    /**
     * @throws \DateInvalidOperationException
     */
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

    public function getMonth(): int
    {
        return (int)$this->begin->format('n');
    }
}
