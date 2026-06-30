<?php

declare(strict_types=1);

namespace App\Modules\Fykos\EventsModule;

use App\Models\Downloader\Services\EventService;
use App\Models\Images\ImageService;
use Nette\Application\BadRequestException;
use Nette\Http\IResponse;
use Nette\InvalidStateException;

class CampsPresenter extends BasePresenter
{
    private const CAMPS_IDS = [4, 5];
    private readonly EventService $eventService;
    private readonly ImageService $imageService;

    public function injectEventService(EventService $eventService): void
    {
        $this->eventService = $eventService;
    }

    public function injectImageService(ImageService $imageService): void
    {
        $this->imageService = $imageService;
    }

    /**
     * @throws \Throwable
     */
    public function renderDetail(int $year, int $season): void
    {
        $events = $this->eventService->getEventsByYear([$season], $year);
        if (count($events) > 1) {
            throw new InvalidStateException(sprintf('More than one event type for a year %d', $year));
        } elseif (count($events) === 0) {
            throw new BadRequestException(
                $this->csen('Akce nenalezena', 'Event not found'),
                IResponse::S404_NotFound
            );
        }

        $event = reset($events);

        $this->template->event = $event;
        $this->template->imageService = $this->imageService;
        $this->template->participants = $this->eventService->getParticipated($event->eventId);
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $events = $this->eventService->getEvents(self::CAMPS_IDS);

        // sort by date
        usort($events, function ($a, $b) {
            return $b->begin <=> $a->begin;
        });

        $coverImages = [];
        foreach ($events as $event) {
            $image =  $this->imageService->getRandomImage($event);
            if (!$image) {
                $coverImages[$event->eventId] = '/images/event-missing-photo.png';
                continue;
            }
            $coverImages[$event->eventId] = $image['src'];
        }

        $this->template->coverImages = $coverImages;
        $this->template->events = $events;
    }
}
