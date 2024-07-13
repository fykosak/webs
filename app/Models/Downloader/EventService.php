<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use App\Models\Downloader\EventOrganizersRequest;
use App\Models\Downloader\FKSDBDownloader;
use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use App\Models\NetteDownloader\ORM\Models\ModelPersonSchedule;
use App\Models\NetteDownloader\ORM\Services\AbstractJSONService;
use Fykosak\FKSDBDownloaderCore\Requests\EventListRequest;
use Fykosak\FKSDBDownloaderCore\Requests\EventRequest;
use Fykosak\FKSDBDownloaderCore\Requests\ParticipantsRequest;
use Nette\Caching\Storage;

final class EventService extends AbstractJSONService
{
    public function __construct(string $expiration, Storage $storage, FKSDBDownloader $downloader)
    {
        $this->downloader = $downloader;
        parent::__construct($expiration, $storage);
    }

    /**
     * @param ModelEvent[] &$events
     * @param int[] $IDs IDs of events to remove from list.
     */
    public static function removeByIDs(array &$events, array $IDs): void
    {
        $events = array_filter($events, function ($event) use ($IDs) {
            return (!in_array($event->eventId, $IDs));
        });
    }

    /**
     * @return EventParticipantModel[]
     */
    public function getEventParticipants(int $eventId, ?string $explicitExpiration = null): array
    {
        return $this->getItem(
            new ParticipantsRequest($eventId),
            [],
            EventParticipantModel::class,
            true,
            $explicitExpiration
        );
    }

    /**
     * @return EventParticipantModel[]
     */
    public function getParticipated(int $eventId, ?string $explicitExpiration = null): array
    {
        return array_filter($this->getEventParticipants($eventId, $explicitExpiration), function ($v) {
            return $v->status == 'participated';
        });
    }

    /**
     * @return EventOrganizerModel[]
     */
    public function getEventOrganizers(int $eventId, ?string $explicitExpiration = null): array
    {
        return $this->getItem(
            new EventOrganizersRequest($eventId),
            [],
            EventOrganizerModel::class,
            true,
            $explicitExpiration
        );
    }

    public function getEvent(int $eventId, ?string $explicitExpiration = null): ModelEvent
    {
        return $this->getItem(
            new EventRequest($eventId),
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
            new EventRequest($eventId),
            ['personSchedule'],
            ModelPersonSchedule::class,
            true,
            $explicitExpiration
        );
    }

    // eventList
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
        usort($items, fn (ModelEvent $a, ModelEvent $b): int => $a->begin <=> $b->begin);
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
            fn (ModelEvent $event): bool => $year === (int)$event->begin->format('Y')
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
