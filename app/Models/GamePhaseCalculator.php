<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Downloader\EventService;
use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use Nette\DI\Container;
use Nette\SmartObject;
use Throwable;

final class GamePhaseCalculator
{
    use SmartObject;

    public function __construct(
        private readonly int $eventTypeId,
        private readonly EventService $eventService,
        private readonly Container $container
    ) {
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
