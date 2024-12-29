<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use App\Models\Downloader\EventService;
use DateTime;
use Nette\Caching\Cache;

class EventsPresenter extends BasePresenter
{
    protected EventService $eventService;

    public function injectEventServicesAndCache(EventService $eventService): void
    {
        $this->eventService = $eventService;
    }

    public function renderDetail(int $event): void
    {
        $event = $this->eventService->getEvent($event);
        if ($event->contestId != 2) {
            $this->error();
        }
        $this->template->event = $event;
        $this->template->hasGallery = $this->getComponent('gallery')->hasPhotos("/media/photos/event/" . $event->eventId);
        $this->template->hasPdfs = $this->getComponent('pdfGallery')->hasFiles("/media/download/event/" . $event->eventId);
        $persons = $event->end < new DateTime() ? $this->eventService->getEventOrganizers($event->eventId) : [];
        $array = [];
        foreach ($persons as $person) {
            $array[] = $person->person->name;
        }
        $this->template->organizers = implode(', ', $array);
        $persons = $event->end < new DateTime() ? $this->eventService->getEventParticipants($event->eventId) : [];
        $array = [];
        foreach ($persons as $person) {
            if ($person->status === 'participated') {
                $array[] = $person->name;
            }
        }
        $this->template->participants = implode(', ', $array);
    }

    public function renderTabor(): void
    {
        $this->template->events = array_reverse($this->eventService->getEvents([10]));
    }

    public function renderSetkani(): void
    {
        $this->template->events = array_reverse($this->eventService->getEvents([11, 12]));
    }
}
