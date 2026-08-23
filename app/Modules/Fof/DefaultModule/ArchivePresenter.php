<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use App\Models\Images\ImageService;

class ArchivePresenter extends BasePresenter
{
    private readonly ImageService $imageService;

    public function inject(ImageService $imageService): void
    {
        $this->imageService = $imageService;
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $events = array_reverse($this->eventService->getEvents($this->getEventIds())); // TODO
        $events = array_filter($events, function ($event) {
            //return true;
            return $event->end < new \DateTime('now');
        });
        $eventKeys = [];
        foreach ($events as $event) {
            $eventKeys[] = [
                'event' => $event,
                'key' => BasePresenter::createEventKey($event),
            ];
        }

        $this->template->imageService = $this->imageService;
        $this->template->eventKeys = $eventKeys;
    }
}
