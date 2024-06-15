<?php

declare(strict_types=1);

namespace App\Modules\Dsef\DefaultModule;

use App\Models\Downloader\EventService;

class ArchivePresenter extends BasePresenter
{
    protected readonly EventService $eventService;

    public function injectEventService(EventService $eventService): void
    {
        $this->eventService = $eventService;
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $events = array_reverse($this->eventService->getEvents(self::EVENT_IDS));
        $events = array_filter($events, function ($event) {
            //return true;
            return $event->end < new \DateTime('now');
        });
        $eventKeys = [];
        foreach ($events as $event) {
            $eventKeys[] = [
                'event' => $event,
                'year' => BasePresenter::getEventYear($event),
                'month' => BasePresenter::getEventMonth($event),
                'fykos-year' => $event->year,
            ];
        }

        $this->template->eventKeys = $eventKeys;
    }
}
