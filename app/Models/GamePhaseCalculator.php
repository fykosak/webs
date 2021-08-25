<?php

declare(strict_types=1);

namespace App\Models;

use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventList;
use Nette\DI\Container;
use Nette\InvalidArgumentException;
use Nette\SmartObject;

class GamePhaseCalculator
{
    use SmartObject;

    public const DATE_KNOWN = 'DATE_KNOWN';
    public const REGISTRATION_BEGIN = 'REGISTRATION_BEGIN';
//public const herní den začal
    public const REGISTRATION_ENDED = 'REGISTRATION_END';
    public const STREAM_BEGAN = 'STREAM_BEGIN';
    public const GAME_STARTED = 'GAME_STARTED';
    public const RESULTS_HIDDEN = 'RESULTS_HIDDEN';
    public const GAME_ENDED = 'GAME_ENDED';
    public const RESULTS_PUBLISHED = 'RESULTS_PUBLISHED';
    public const STREAM_ENDED = 'STREAM_ENDED';
// public const herní den skončil
// public const herní víkend skončil
    public const COMPETITION_END = 'COMPOTITION_END';

    private ServiceEventList $serviceEventList;
    private Container $container;

    private const ORDER = [
        self::DATE_KNOWN,
        self::REGISTRATION_BEGIN,
        self::REGISTRATION_ENDED,
        self::STREAM_BEGAN,
        self::GAME_STARTED,
        self::RESULTS_HIDDEN,
        self::GAME_ENDED,
        self::RESULTS_PUBLISHED,
        self::STREAM_ENDED,
        self::COMPETITION_END,
    ];

    public function getLastEvent(): string
    {
        $fksdbEvent = $this->getFKSDBEvent();
        $date = new \DateTime();
        if ($date < $fksdbEvent->registrationBegin) {
            return self::DATE_KNOWN;
        }
        if ($date < $fksdbEvent->registrationEnd) {
            return self::REGISTRATION_BEGIN;
        }
        if ($date < $fksdbEvent->begin && !isset($this->container->getParameters()['streamURL'])) {
            return self::REGISTRATION_ENDED;
        }
        if (isset($this->container->getParameters()['streamURL'])) {
            return self::STREAM_BEGAN;
        }
        if ($date > $fksdbEvent->begin && $date < $fksdbEvent->end) {
            return self::GAME_STARTED;
        }
        // TODO gamesetup
        if ($date > $fksdbEvent->end && isset($this->container->getParameters()['streamURL'])) {
            return self::GAME_ENDED;
        }
        // todo 'RESULTS_PUBLISHED';
        if ($date > $fksdbEvent->end) {
            return self::STREAM_ENDED;
        }
        return self::COMPETITION_END;
    }

    public function isAfter(string $phase): bool
    {
        return $this->getPhaseIndex($phase) >= $this->getPhaseIndex($this->getLastEvent());
    }

    public function isBefore(string $phase): bool
    {
        return $this->getPhaseIndex($phase) < $this->getPhaseIndex($this->getLastEvent());
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

    private function getPhaseIndex(string $phase): int
    {
        $phaseIndex = array_search($phase, self::ORDER, true);
        if ($phaseIndex === false) {
            throw new InvalidArgumentException(sprintf('Item %s does not exists in phase order', $phase));
        }
        return $phaseIndex;
    }
}
