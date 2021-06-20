<?php

namespace App\Components\EventList;

use App\Modules\Core\BasePresenter;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventList;
use Fykosak\Utils\BaseComponent\BaseComponent;

class EventListComponent extends BaseComponent {

    protected ServiceEventList $serviceEvent;

    public function injectServiceEvent(ServiceEventList $serviceEvent): void {
        $this->serviceEvent = $serviceEvent;
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function render(): void {
        $events = array_reverse($this->serviceEvent->getEvents([9]));
        $eventKeys = [];
        foreach ($events as $event)
            $eventKeys[] = [
                'event' => $event,
                'key' => BasePresenter::createEventKey($event),
            ];

        $this->template->eventKeys = $eventKeys;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'eventList.latte');
    }
}
