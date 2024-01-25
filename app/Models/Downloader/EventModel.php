<?php

declare(strict_types=1);

namespace App\Models\Event;

use App\Models\Downloader\FKSDBDownloader;
use Nette\Caching\Cache;
use Fykosak\FKSDBDownloaderCore\Requests\EventListRequest;

class EventModel
{
    public int $eventId;
    public ?int $year;
    public ?int $eventYear;
    public ?string $begin;
    public ?string $end;
    public ?string $registrationBegin;
    public ?string $registrationEnd;
    public ?string $report;
    public ?string $description;
    public ?string $name;
    public int $eventTypeId;
    public ?string $place;
    public ?int $contestId;

    public static function getOrderedEventsByTypes(
        FKSDBDownloader $downloader,
        array $types,
        array $blacklistedIds,
        string $lang
    ): array {
        $cache = $downloader->getCache();
        return $cache->load(
            "eventModel.getOrderedEventsByTypes." . $lang . "." . join("-", $types) . ".blacklist."
                . join("-", $blacklistedIds),
            function (&$dependencies) use ($downloader, $types, $blacklistedIds, $lang) {
                $dependencies[Cache::Expire] = $downloader->getExpiration();
                $dependencies[Cache::Files] = '/workspace/app/Models/Downloader/EventModel.php';
                return EventModel::getOrderedEventsByTypesNoCache($downloader, $types, $blacklistedIds, $lang);
            }
        );
    }

    public static function getOrderedEventsByTypesNoCache(
        FKSDBDownloader $downloader,
        array $types,
        array $blacklistedIds,
        string $lang
    ): array {
        $events = $downloader->download(new EventListRequest($types));
        $events = array_filter($events, function ($event) use ($blacklistedIds) {
            return (!in_array($event['eventId'], $blacklistedIds));
        });
        $orderedEvents = [];
        foreach ($events as $key => $event) {
            $resultEvent = new EventModel();
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
            $orderedEvents[$event['begin'] . $key] = $resultEvent;
        }
        krsort($orderedEvents);
        return $orderedEvents;
    }
}
