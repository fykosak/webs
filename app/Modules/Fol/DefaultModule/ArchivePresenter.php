<?php

declare(strict_types=1);

namespace App\Modules\Fol\DefaultModule;

use App\Models\Downloader\EventService;

class ArchivePresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $events = array_reverse($this->eventService->getEvents([$this->getContext()->getParameters()['eventTypeId']]));
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
