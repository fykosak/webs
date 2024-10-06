<?php

declare(strict_types=1);

namespace App\Modules\Fykos\EventsModule;

use Fykosak\FKSDBDownloaderCore\Requests\EventListRequest;
use Fykosak\FKSDBDownloaderCore\Requests\ParticipantsRequest;
use Nette\Application\BadRequestException;
use Nette\Http\IResponse;

class CampsPresenter extends BasePresenter
{
    private const CAMPS_IDS = [4, 5];

    public function getEventHeading(array $event): array
    {
        if ($event['eventTypeId'] == 4) {
            $year = 1986 + $event['year'] + 1;
            return [
                'cs' => 'Jarní soustředění ' . $year,
                'en' => 'Spring camp ' . $year,
            ];
        } else { // $event['eventTypeId'] == 5
            $year = 1986 + $event['year'];
            return [
                'cs' => 'Podzimní soustředění ' . $year,
                'en' => 'Autumn camp ' . $year,
            ];
        }
    }

    public function eventYearToCalendarYear(int $eventYear, int $season): int
    {
        if ($season == 4) {
            return 1986 + $eventYear + 1;
        } else { // $season == 5
            return 1986 + $eventYear;
        }
    }

    /**
     * @throws \Throwable
     */
    public function renderDetail(int $year, int $season): void
    {
        $events = $this->downloader->download(new EventListRequest([$season]));

        $event = null;
        foreach ($events as $e) {
            if ($this->eventYearToCalendarYear($e['year'], $e['eventTypeId']) == $year) {
                $event = $e;
                break;
            }
        }

        if ($event === null) {
            throw new BadRequestException(
                $this->csen('Stránka nenalezena', 'Page not found'),
                IResponse::S404_NotFound
            );
        }

        $event['heading'] = $this->getEventHeading($event);
        $this->template->event = $event;
        $this->template->photoPath = $this->getEventPhotoBasePath($event);
        $participants = $this->downloader->download(new ParticipantsRequest((int)$event['eventId']));
        $participants = array_filter($participants, function ($participant) {
            return $participant['status'] == 'participated';
        });
        $this->template->participants = $participants;
    }

    private function getEventPhotoBasePath(array $event): string
    {
        if ($event['eventTypeId'] == 4) {
            $eventType = 'sous-jaro';
        } else { // $event['eventTypeId'] == 5
            $eventType = 'sous-podzim';
        }
        if ($event['year'] < 10) {
            $fullYear = '0' . $event['year'];
        } else {
            $fullYear = $event['year'];
        }

        return '/media/images/events/' . $eventType . '/rocnik' . $fullYear . '/carousel-photos';
    }

    public function getEventPhoto(array $event): string
    {
        $photos = glob($this->getEventPhotoBasePath($event) . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        if (empty($photos)) {
            return $this->template->basePath . '/media/images/events/event-missing-photo.png';
        }

        $photo = $photos[array_rand($photos)];
        return substr($photo, 1); // remove leading dot
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $events = $this->downloader->download(new EventListRequest(self::CAMPS_IDS));

        // sort by date
        usort($events, function ($a, $b) {
            return strtotime($b['begin']) - strtotime($a['begin']);
        });

        foreach ($events as &$event) {
            $event['heading'] = $this->getEventHeading($event);
            $event['photo'] = $this->getEventPhoto($event);
        }

        $this->template->events = $events;
    }
}
