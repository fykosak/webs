<?php

namespace App\Model\ORM;

use Exception;

class ServiceEvent extends AbstractSOAPService {
    /** @var ModelEvent[] */
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
            }
            usort($this->events, function (ModelEvent $a, ModelEvent $b) {
                return $a->begin <=> $b->begin;
            });
        }
    }

    /**
     * @param int $year
     * @return ModelEvent|null
     * @throws Exception
     */
    public function getEventByYear(int $year): ?ModelEvent {
        $this->loadEvents();
        foreach ($this->events as $event) {
            if ($event->eventYear === $year) {
                return $event;
            }
        }
        return null;
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