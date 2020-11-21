<?php

namespace App\Model\ORM;

use App\Model\Soap\FKSDBDownloader;
use Exception;
use Tracy\Debugger;

class ServiceEvent extends AbstractSOAPService {

    protected FKSDBDownloader $downloader;

    private array $events;

    /**
     * @throws Exception
     */
    public function loadEvents(): void {
        if (!isset($this->events)) {
            $xml = $this->downloader->createEventList();
            $doc = new \DOMDocument();
            $doc->loadXML($xml);
            foreach ($doc->getElementsByTagName('event') as $eventNode) {
                $event = ModelEvent::createFromXMLNode($eventNode);
                $this->events[] = $event;
                Debugger::barDump(isset($event->begin));;
            }
            usort($this->events, function (ModelEvent $a, ModelEvent $b) {
                return $a->begin <=> $b->begin;
            });
        }
    }

    /**
     * @return ModelEvent
     * @throws Exception
     */
    public function getNewest(): ModelEvent {
        $this->loadEvents();
        return end($this->events);
    }
}