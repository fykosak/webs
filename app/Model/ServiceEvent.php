<?php

namespace App\Model;

use App\Model\Soap\FKSDBDownloader;
use Exception;
use Nette\SmartObject;

class ServiceEvent {
    use SmartObject;

    protected FKSDBDownloader $downloader;

    private array $events = [];

    /**
     * ServiceEvent constructor.
     * @param FKSDBDownloader $downloader
     * @throws Exception
     */
    public function __construct(FKSDBDownloader $downloader) {
        $this->downloader = $downloader;
        $this->loadEvents();
    }

    /**
     * @throws Exception
     */
    private function loadEvents(): void {
        $xml = $this->downloader->createEventList();
        $doc = new \DOMDocument();
        $doc->loadXML($xml);
        foreach ($doc->getElementsByTagName('event') as $eventNode) {
            $this->events[] = ModelEvent::createFromXMLNode($eventNode);
        }
        sort($this->events, function (ModelEvent $a, ModelEvent $b) {
            return $a->begin <=> $b->begin;
        });
    }

    public function getNewest(): ModelEvent {
        return end($this->events);
    }
}