<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use App\Models\NetteDownloader\ORM\Services\ServiceEventList;
use Nette\DI\Container;
use Nette\SmartObject;
use Throwable;

class GamePhaseCalculator
{
    use SmartObject;

    private ServiceEventList $serviceEventList;
    private Container $container;

    private int $eventTypeId;

    public function __construct(int $eventTypeId, ServiceEventList $serviceEventList, Container $container)
    {
        $this->eventTypeId = $eventTypeId;
        $this->serviceEventList = $serviceEventList;
        $this->container = $container;
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
        return $this->getFKSDBEvent()->isLongAfterTheEvent();
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
            $fksdbEvent[$this->eventTypeId] = $this->serviceEventList->getNewest([$this->eventTypeId]);
        }
        return $fksdbEvent[$this->eventTypeId];
    }
}
