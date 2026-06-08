<?php

declare(strict_types=1);

namespace App\Modules\Fykos\EventsModule;

use App\Models\Downloader\Services\EventService;

class InternshipsPresenter extends BasePresenter
{
    private const INTERNSHIPS_IDS = [19];

    private readonly EventService $eventService;

    public function injectEventService(EventService $eventService): void
    {
        $this->eventService = $eventService;
    }

    public function renderDefault(): void
    {
        $events = $this->eventService->getEvents(self::INTERNSHIPS_IDS);
        $eventsById = [];
        foreach ($events as $event) {
            $eventsById[$event->eventId] = $event;
        }
        bdump($eventsById);
        $this->template->eventsById = $eventsById;
    }
}
