<?php

declare(strict_types=1);

namespace App\Modules\Dsef\DefaultModule;

class ArchivePresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $events = array_reverse($this->eventService->getEvents(self::EVENT_IDS));
        $events = array_filter($events, function ($event) {
            //return true;
            return $event->end < new \DateTime('now');
        });
        $eventKeys = [];
        foreach ($events as $event) {
            $eventKeys[] = [
                'event' => $event,
                'year' => $event->getYear(),
                'month' => $event->getMonth(),
                'contestYear' => $event->year,
            ];
        }

        $this->template->eventKeys = $eventKeys;
    }
}
