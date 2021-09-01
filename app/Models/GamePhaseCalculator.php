<?php

declare(strict_types=1);

namespace App\Models;

use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventList;
use Nette\DI\Container;
use Nette\SmartObject;

class GamePhaseCalculator
{
    use SmartObject;

    private ServiceEventList $serviceEventList;
    private Container $container;
    /**
     * @return bool
     * @throws \Throwable
     */
    public function isDateKnown(): bool
    {
        return new \DateTime() < $this->getFKSDBEvent()->end;
    }

    public function isRegistrationOpen(): bool
    {
        return new \DateTime() > $this->getFKSDBEvent()->registrationBegin
            && new \DateTime() < $this->getFKSDBEvent()->registrationEnd;
    }

    public function getStreamURL(): ?string
    {
        return $this->container->getParameters()['streamURL'] ?? null;
    }

    public function isGameRunning(): bool
    {
        return new \DateTime() > $this->getFKSDBEvent()->begin && new \DateTime() < $this->getFKSDBEvent()->end;
    }

    public function isResultsVisible(): bool
    {
        return $this->isGameRunning();/*&& new \DateTime() <  TODO*/
    }

    public function isResultsPubliched(): bool
    {
        return false;// TODO;
    }

    /**
     * @return ModelEvent|null
     * @throws \Throwable
     */
    private function getFKSDBEvent(): ?ModelEvent
    {
        static $fksdbEvent;
        if (!isset($this->event)) {
            $fksdbEvent = $this->serviceEventList->getNewest([9]);
        }
        return $fksdbEvent;
    }
}
