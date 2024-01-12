<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Services;

use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use App\Models\NetteDownloader\ORM\Models\ModelPersonSchedule;
use Fykosak\FKSDBDownloaderCore\Requests\EventRequest;
use Fykosak\FKSDBDownloaderCore\Requests\Request;

/**
 * @deprecated
 */
final class ServiceEventDetail extends AbstractJSONService
{
    protected function getRequest(int $eventId): Request
    {
        return new EventRequest($eventId);
    }

    /**
     * @throws \Throwable
     */
    public function getEvent(int $eventId, ?string $explicitExpiration = null): ModelEvent
    {
        return $this->getItem(
            $this->getRequest($eventId),
            [],
            ModelEvent::class,
            false,
            $explicitExpiration
        );
    }

    /**
     * @return ModelPersonSchedule[]
     * @throws \Throwable
     */
    public function getPersonSchedule(int $eventId, ?string $explicitExpiration = null): array
    {
        return $this->getItem(
            $this->getRequest($eventId),
            ['personSchedule'],
            ModelPersonSchedule::class,
            true,
            $explicitExpiration
        );
    }
}
