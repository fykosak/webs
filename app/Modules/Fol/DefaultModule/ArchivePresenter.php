<?php

declare(strict_types=1);

namespace App\Modules\Fol\DefaultModule;

use App\Models\Downloader\EventService;

class ArchivePresenter extends BasePresenter
{
    protected EventService $eventService;

    public function injectEventService(EventService $eventService): void
    {
        $this->eventService = $eventService;
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $events = array_reverse($this->eventService->getEvents([$this->context->getParameters()['eventTypeId']]));
        $events = array_filter($events, function ($event) {
            return $event->end < new \DateTime('now');
        });
        $eventKeys = [];
        foreach ($events as $event) {
            $eventKeys[] = [
                'event' => $event,
                'key' => BasePresenter::createEventKey($event),
            ];
        }

        $this->template->historicalEvents = $eventKeys;
    }
}
