<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use App\Models\Downloader\EventService;

class ArchivePresenter extends BasePresenter
{
    protected EventService $serviceEvent;

    public function injectServiceEvent(EventService $serviceEvent): void
    {
        $this->serviceEvent = $serviceEvent;
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $events = array_reverse($this->serviceEvent->getEvents([$this->context->getParameters()['eventTypeId']]));
        $events = array_filter($events, function ($event) {
            //return true;
            return $event->end < new \DateTime('now');
        });
        $eventKeys = [];
        foreach ($events as $event) {
            $eventKeys[] = [
                'event' => $event,
                'key' => BasePresenter::createEventKey($event),
            ];
        }

        $this->template->eventKeys = $eventKeys;
    }
}
