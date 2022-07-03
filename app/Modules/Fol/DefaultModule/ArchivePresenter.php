<?php

declare(strict_types=1);

namespace App\Modules\Fol\DefaultModule;

use App\Components\Map\MapComponent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventList;

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
        $events = array_reverse($this->serviceEvent->getEvents([$this->context->getParameters()["eventTypeId"]]));
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

        $this->template->eventKeys = $eventKeys;
    }
}
