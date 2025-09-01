<?php

declare(strict_types=1);

namespace App\Models\Downloader\Services;

use App\Models\Downloader\Downloaders\FKSDBDownloader;
use App\Models\Downloader\Requests\EventOrganizersRequest;
use App\Models\Downloader\Models\EventModel;
use App\Models\Downloader\Models\EventOrganizerModel;
use App\Models\Downloader\Models\EventParticipantModel;
use App\Models\Downloader\Models\PersonScheduleModel;
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
     * @param EventModel[] &$events
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

    public function getEvent(int $eventId, ?string $explicitExpiration = null): EventModel
    {
        return $this->getItem(
            new EventRequest($eventId),
            [],
            EventModel::class,
            false,
            $explicitExpiration
        );
    }

    /**
     * @return PersonScheduleModel[]
     * @throws \Throwable
     */
    public function getPersonSchedule(int $eventId, ?string $explicitExpiration = null): array
    {
        return $this->getItem(
            new EventRequest($eventId),
            ['personSchedule'],
            PersonScheduleModel::class,
            true,
            $explicitExpiration
        );
    }

    // eventList
    /**
     * @param int[] $eventTypeIds
     * @return EventModel[]
     * @throws \Throwable
     */
    public function getEvents(array $eventTypeIds, ?string $explicitExpiration = null): array
    {
        $items = $this->getItem(
            new EventListRequest($eventTypeIds),
            [],
            EventModel::class,
            true,
            $explicitExpiration
        );
        usort($items, fn(EventModel $a, EventModel $b): int => $a->begin <=> $b->begin);
        return $items;
    }

    /**
     * @param int[] $eventTypeIds
     * @return EventModel[]
     * @throws \Throwable
     */
    public function getEventsByYear(array $eventTypeIds, int $year, ?string $explicitExpiration = null): array
    {
        return array_filter(
            $this->getEvents($eventTypeIds, $explicitExpiration),
            fn(EventModel $event): bool => $year === (int)$event->begin->format('Y')
        );
    }

    /**
     * @param int[] $eventTypeIds
     * @throws \Throwable
     */
    public function getNewest(array $eventTypeIds, ?string $explicitExpiration = null): EventModel
    {
        $events = $this->getEvents($eventTypeIds, $explicitExpiration);
        return end($events);
    }
}
