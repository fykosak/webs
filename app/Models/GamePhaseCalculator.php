<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeInterface;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventList;
use Nette\ArgumentOutOfRangeException;
use Nette\DI\Container;
use Nette\SmartObject;

class GamePhaseCalculator
{
    use SmartObject;

    private ServiceEventList $serviceEventList;
    private Container $container;

    public const BEFORE = 0;
    public const AFTER = 1;
    public const NOW = 2;

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

    public function __construct(ServiceEventList $serviceEventList, Container $container)
    {
        $this->serviceEventList = $serviceEventList;
        $this->container = $container;
    }

    /**
     * @throws \Throwable
     */
    public function isDateKnown(): bool
    {
        return new \DateTime() < $this->getFKSDBEvent()->end;
    }

    /**
     * @throws \Throwable
     */
    public function isRegistration(int $period): bool
    {
        return $this->checkEvent(
            $period,
            $this->getFKSDBEvent()->registrationBegin,
            $this->getFKSDBEvent()->registrationEnd,
        );
    }

    public function isGame(int $period): bool
    {
        // todo implement
        switch ($period) {
            case self::BEFORE:
                return true;
            default:
                return false;
        }
    }

    /**
     * @throws \Throwable
     */
    public function isAfterRegistration(): bool
    {
        return $this->isDateKnown() && new \DateTime() > $this->getFKSDBEvent()->registrationEnd;
    }

    public function isBeforeTheCompetition(): bool
    {
        return true; // todo implement
    }

    public function getStreamURL(): ?string
    {
        return $this->container->getParameters()['streamURL'] ?? null;
    }

    /**
     * @throws \Throwable
     */
    public function isGameRunning(): bool
    {
        return new \DateTime() > $this->getFKSDBEvent()->begin && new \DateTime() < $this->getFKSDBEvent()->end;
    }

    /**
     * @throws \Throwable
     */
    public function isResultsVisible(): bool
    {
        return $this->isGameRunning();/*&& new \DateTime() <  TODO*/
    }

    public function isResultsPublished(): bool
    {
        return false;// TODO;
    }

    /**
     * Returns newest FKSDB event. That means by creating a new one, the application automatically switches to the new
     * year.
     * @throws \Throwable
     */
    public function getFKSDBEvent(): ?ModelEvent
    {
        static $fksdbEvent;
        if (!isset($fksdbEvent)) {
            $fksdbEvent = $this->serviceEventList->getNewest([9]);
        }
        return $fksdbEvent;
    }
}
