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

    public ModelGame|null $game;
    public Period $registration;

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
        return (int) $this->begin->format('Y');
    }

    public function getMonth(): string
    {
        return $this->begin->format('m');
    }

    public function makeParagraphs(string $attr): ModelEvent
    {
        if (is_string($this->{$attr})) {
            $this->{$attr} = $this->makeOneParagraphs($this->{$attr});
        } elseif (is_array($this->{$attr})) {
            foreach ($this->{$attr} as $key => $value) {
                $this->{$attr}[$key] = $this->makeOneParagraphs($value);
            }
        }
        return $this;
    }

    private function makeOneParagraphs(?string $s): ?string
    {
        if ($s === null) {
            return null;
        }
        $arr = mb_split("\n\n", $s);
        foreach ($arr as $key => $value) {
            $value = trim(implode(' ', mb_split("\\s+", $value)));
            if ($value === '')
                unset($arr[$key]);
            else
                $arr[$key] = "<p>$value</p>";
        }
        return implode('', $arr);
    }
}
