<?php

declare(strict_types=1);

namespace App\Models\NetteDownloader\ORM\Services;

use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use Fykosak\FKSDBDownloaderCore\Requests\EventListRequest;

final class ServiceEventList extends AbstractJSONService
{
    /**
     * @param int[] $eventTypeIds
     * @return ModelEvent[]
     * @throws \Throwable
     */
    public function getEvents(array $eventTypeIds, ?string $explicitExpiration = null): array
    {
        $items = $this->getItem(
            new EventListRequest($eventTypeIds),
            [],
            ModelEvent::class,
            true,
            $explicitExpiration
        );
        usort($items, fn(ModelEvent $a, ModelEvent $b): int => $a->begin <=> $b->begin);
        return $items;
    }

    /**
     * @param int[] $eventTypeIds
     * @return ModelEvent[]
     * @throws \Throwable
     */
    public function getEventsByYear(array $eventTypeIds, int $year, ?string $explicitExpiration = null): array
    {
        return array_filter(
            $this->getEvents($eventTypeIds, $explicitExpiration),
            fn(ModelEvent $event): bool => $year === (int)$event->begin->format('Y')
        );
    }

    /**
     * @param int[] $eventTypeIds
     * @throws \Throwable
     */
    public function getNewest(array $eventTypeIds, ?string $explicitExpiration = null): ModelEvent
    {
        $events = $this->getEvents($eventTypeIds, $explicitExpiration);
        return end($events);
    }
}