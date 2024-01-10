<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Services;

use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use App\Models\NetteDownloader\ORM\Models\ModelPersonSchedule;
use App\Models\NetteDownloader\ORM\Models\ModelSchedule;
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
     * @return ModelSchedule[]
     */
    public function getSchedule(int $eventId, ?string $explicitExpiration = null): array
    {
        return $this->getItem(
            $this->getRequest($eventId),
            ['schedule'],
            ModelSchedule::class,
            true,
            $explicitExpiration
        );
    }

    /**
     * @return ModelPersonSchedule[]
     */
    public function getPersonSchedule(int $eventId, ?string $explicitExpiration = null): array
    {
        return $this->getItem(
            $this->getRequest($eventId),
            ['person_schedule'],
            ModelPersonSchedule::class,
            true,
            $explicitExpiration
        );
    }
}
