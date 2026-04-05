<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use App\Models\Downloader\Services\EventService;
use DateTime;

class EventsPresenter extends BasePresenter
{
    protected EventService $eventService;

    public function injectEventServicesAndCache(EventService $eventService): void
    {
        $this->eventService = $eventService;
    }

    public function renderDetail(int $eventId): void
    {
        $event = $this->eventService->getEvent($eventId);
        if ($event->contestId !== 2) {
            $this->error();
        }
        $this->template->event = $event;
        $this->template->hasGallery = $this->getComponent('gallery')->hasPhotos("/media/photos/event/" . $event->eventId);
        $this->template->hasPdfs = $this->getComponent('pdfGallery')->hasFiles("/media/download/event/" . $event->eventId);

        $eventOrganizers = $event->end < new DateTime() ? $this->eventService->getEventOrganizers($event->eventId) : [];
        $organizers = [];
        foreach ($eventOrganizers as $eventOrganizer) {
            $organizers[] = $eventOrganizer->person->name;
        }
        $this->template->organizers = implode(', ', $organizers);

        $eventParticipants = $event->end < new DateTime() ? $this->eventService->getEventParticipants($event->eventId) : [];
        $participants = [];
        foreach ($eventParticipants as $participant) {
            if ($participant->status === 'participated') {
                $participants[] = $participant->name;
            }
        }
        $this->template->participants = implode(', ', $participants);
    }

    public function renderTabor(): void
    {
        $this->template->events = array_reverse($this->eventService->getEvents([10]));
    }

    public function renderSetkani(): void
    {
        $this->template->events = array_reverse($this->eventService->getEvents([11, 12]));
    }

    public function renderVikendovka(): void
    {
        $this->template->events = array_reverse($this->eventService->getEvents([18]));
    }
}
