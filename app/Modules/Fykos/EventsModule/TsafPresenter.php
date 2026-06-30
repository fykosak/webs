<?php

declare(strict_types=1);

namespace App\Modules\Fykos\EventsModule;

use App\Models\Downloader\Services\EventService;
use Fykosak\FKSDBDownloaderCore\Requests\EventListRequest;
use Fykosak\FKSDBDownloaderCore\Requests\ParticipantsRequest;
use Nette\Application\BadRequestException;
use Nette\Http\IResponse;

class TsafPresenter extends BasePresenter
{
    private const TSAF_IDS = [6, 7];

    private readonly EventService $eventService;

    public function injectEventService(EventService $eventService): void
    {
        $this->eventService = $eventService;
    }

    /**
     * @throws \Throwable
     */
    public function renderDetail(int $year, int $month): void
    {
        // filter events by year
        $events = $this->eventService->getEvents(self::TSAF_IDS);

        $event = null;
        foreach ($events as $e) {
            if ($e->getYear() === $year && $e->getMonth() === $month) {
                $event = $e;
                break;
            }
        }

        if ($event === null) {
            throw new BadRequestException(
                $this->csen('Stránka nenalezena', 'Page not found'),
                IResponse::S404_NotFound
            );
        }

        $this->template->event = $event;
        $this->template->participants = $this->eventService->getParticipated($event->eventId);
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $events = $this->eventService->getEvents(self::TSAF_IDS);

        // sort by date
        usort($events, function ($a, $b) {
            return $b->begin <=> $a->begin;
        });

        $coverImages = [];
        foreach ($events as $event) {
            $coverImages[$event->eventId] = '/images/event-missing-photo.png';
        }

        $this->template->coverImages = $coverImages;
        $this->template->events = $events;
    }

    public function eventHasDate(array $event, string $year, string $month): bool
    {
        $eventBegin = strtotime($event['begin']);
        return date('Y', $eventBegin) === $year && date('m', $eventBegin) === $month;
    }
}
