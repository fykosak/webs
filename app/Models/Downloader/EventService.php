<?php

declare(strict_types=1);

namespace App\Models\Downloader;

use App\Models\Downloader\FKSDBDownloader;
use App\Models\NetteDownloader\ORM\Services\AbstractJSONService;
use Fykosak\FKSDBDownloaderCore\Requests\EventRequest;
use Fykosak\FKSDBDownloaderCore\Requests\Request;
use Fykosak\FKSDBDownloaderCore\Requests\EventListRequest;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use App\Models\NetteDownloader\ORM\Models\ModelPersonSchedule;

final class EventService extends AbstractJSONService
{
    public function __construct(string $expiration, Storage $storage, FKSDBDownloader $downloader)
    {
        $this->downloader = $downloader;
        parent::__construct($expiration, $storage);
    }

   /* public function getLocalizedEventsByType(array $types, string $lang): array
    {
        sort($types);
        return $this->cache->load(
            "getEventsByType." . $lang . "." . join("-", $types),
            function (&$dependencies) use ($types, $lang) {
                $dependencies[Cache::Expire] = $this->expiration;
                $json = $this->downloader->download(new EventListRequest($types));
                $events = [];
                foreach ($json as $event) {
                    $resultEvent = new LocalizedEventModel();
                    $resultEvent->eventId = $event['eventId'];
                    $resultEvent->eventTypeId = $event['eventTypeId'];
                    $resultEvent->contestId = $event['contestId'];
                    $resultEvent->year = $event['year'];
                    $resultEvent->eventYear = $event['eventYear'];
                    $resultEvent->begin = $event['begin'];
                    $resultEvent->end = $event['end'];
                    $resultEvent->registrationBegin = $event['registrationBegin'];
                    $resultEvent->registrationEnd = $event['registrationEnd'];
                    $resultEvent->place = $event['place'];
                    $resultEvent->description = $event['description'][$lang];
                    if ($event['nameNew'][$lang]) {
                        $resultEvent->name = $event['nameNew'][$lang];
                    } else {
                        $resultEvent->name = $event['name'];
                    }
                    if ($event['nameNew'][$lang]) {
                        $resultEvent->report = $event['reportNew'][$lang];
                    } else {
                        $resultEvent->report = $event['report'];
                    }
                    $events[] = $resultEvent;
                }
                return $events;
            }
        );
    }*/

    /**
     * @param EventModel[] $events
     */
    public function orderEvents(array &$events): void
    {
        usort($events, fn (ModelEvent $a, ModelEvent $b): int => $a->begin <=> $b->begin);
    }

    /**
     * @param EventModel[] &$events
     * @param int[] $IDs IDs of events to remove from list.
     */
    public function removeByIDs(array &$events, array $IDs): void
    {
        $events = array_filter($events, function ($event) use ($IDs) {
            return (!in_array($event->eventId, $IDs));
        });
    }

    //detail

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
