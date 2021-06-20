<?php

namespace App\Components\EventList;

use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventList;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Exception;
use Nette\DI\Container;
use Throwable;

class EventListComponent extends BaseComponent {

    protected ServiceEventList $serviceEvent;

    public function __construct(Container $container) {
        parent::__construct($container);
    }

    public function injectServiceEvent(ServiceEventList $serviceEvent): void {
        $this->serviceEvent = $serviceEvent;
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function render(): void {
        $events = array_reverse($this->serviceEvent->getEvents([9]));
        $eventKeys = [];
        foreach ($events as $event)
            $eventKeys[] = [
                'event' => $event,
                'key' => \App\Modules\Core\BasePresenter::createEventKey($event)
            ];

        $this->template->eventKeys = $eventKeys;

        //\Tracy\Debugger::barDump($this->template->events);
        //\Tracy\Debugger::barDump($this->template->keys);
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'eventList.latte');
    }
}
