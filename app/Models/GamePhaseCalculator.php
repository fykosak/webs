<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Downloader\EventService;
use DateTimeInterface;
use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use Nette\ArgumentOutOfRangeException;
use Nette\DI\Container;
use Nette\SmartObject;
use Throwable;

class GamePhaseCalculator
{
    use SmartObject;

    private EventService $eventService;
    private Container $container;

    public const BEFORE = 0;
    public const AFTER = 1;
    public const NOW = 2;
    private int $eventTypeId;

    public function __construct(int $eventTypeId, EventService $eventService, Container $container)
    {
        $this->eventTypeId = $eventTypeId;
        $this->eventService = $eventService;
        $this->container = $container;
    }

    protected function checkEvent(int $period, DateTimeInterface $start, DateTimeInterface $end): bool
    {
        $now = new \DateTime();
        switch ($period) {
            case self::BEFORE:
                return $now < $start;
            case self::AFTER:
                return $now > $end;
            case self::NOW:
                return $now > $start && $now < $end;
            default:
                throw new ArgumentOutOfRangeException('Invalid period');
        }
    }

    /**
     * @throws Throwable
     */
    public function isDateKnown(): bool
    {
        return new \DateTime() < $this->getFKSDBEvent()->end;
    }

    /**
     * @throws Throwable
     */
    public function isRegistration(int $period): bool
    {
        return $this->checkEvent(
            $period,
            $this->getFKSDBEvent()->registrationBegin,
            $this->getFKSDBEvent()->registrationEnd,
        );
    }

    /**
     * @throws Throwable
     */
    public function isNearTheCompetition(int $period): bool
    {
        $begin = (new \DateTime())->setTimestamp($this->getFKSDBEvent()->begin->getTimestamp())
            ->sub(new \DateInterval('P3D'));
        $end = (new \DateTime())->setTimestamp($this->getFKSDBEvent()->begin->getTimestamp())
            ->add(new \DateInterval('P1D'));
        return $this->checkEvent(
            $period,
            $begin,
            $end,
        );
    }

    /**
     * The game itself, three hours long
     * @throws Throwable
     */
    public function isGame(int $period = self::NOW): bool
    {
        return $this->checkEvent(
            $period,
            $this->getGameBegin(),
            $this->getGameBegin()->add(new \DateInterval('PT3H')),
        );
    }

    /**
     * @throws Throwable
     */
    public function isAfterRegistration(): bool
    {
        return $this->isDateKnown() && new \DateTime() > $this->getFKSDBEvent()->registrationEnd;
    }

    /**
     * @throws Throwable
     */
    public function getGameBegin(): \DateTime
    {
        $time = new \DateTime($this->container->getParameters()['competitionBegin']);
        $day = $this->getFKSDBEvent()->begin;

        $time->setDate((int)$day->format('Y'), (int)$day->format('m'), (int)$day->format('d'));
        return $time;
    }

    /**
     * Returns true about a week after the event when no one is interested in game already.
     * @throws Throwable
     */
    public function isLongAfterTheEvent(): bool
    {
        $event = (new \DateTime())->setTimestamp($this->getFKSDBEvent()->end->getTimestamp())
            ->add(new \DateInterval('P7D'));
        return new \DateTime() > $event;
    }

    /**
     * @throws Throwable
     */
    public function isResultsVisible(): bool
    {
        return $this->isGame();
    }

    /**
     * Returns newest FKSDB event. That means by creating a new one, the application automatically switches to the new
     * year.
     * @throws Throwable
     */
    public function getFKSDBEvent(): ?ModelEvent
    {
        static $fksdbEvent;
        if (!isset($fksdbEvent[$this->eventTypeId])) {
            $fksdbEvent[$this->eventTypeId] = $this->eventService->getNewest([$this->eventTypeId]);
        }
        return $fksdbEvent[$this->eventTypeId];
    }
}
