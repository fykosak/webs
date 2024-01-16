<?php

declare(strict_types=1);

namespace App\Modules\Dsef\DefaultModule;

use App\Models\NetteDownloader\ORM\Services\ServiceEventList;

class ArchivePresenter extends BasePresenter
{
    protected ServiceEventList $serviceEvent;

    public function injectServiceEvent(ServiceEventList $serviceEvent): void
    {
        $this->serviceEvent = $serviceEvent;
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $events = array_reverse($this->serviceEvent->getEvents(self::EVENT_IDS));
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
