<?php

declare(strict_types=1);

namespace App\Modules\Fykos\EventsModule;

use Fykosak\FKSDBDownloaderCore\Requests\EventListRequest;
use Fykosak\FKSDBDownloaderCore\Requests\ParticipantsRequest;
use Nette\Application\BadRequestException;
use Nette\Http\IResponse;

class TsafPresenter extends BasePresenter
{
    private const TSAF_IDS = [6, 7];

    /**
     * @throws \Throwable
     */
    public function renderDetail(int $year, int $month): void
    {
        // filter events by year

        $events = $this->downloader->download(new EventListRequest(self::TSAF_IDS));

        $event = null;
        foreach ($events as $e) {
            $eventBegin = strtotime($e['begin']);
            if (date('Y', $eventBegin) == $year && date('m', $eventBegin) == $month) {
                $event = $e;
                break;
            }
        }

        if ($event === null) {
            throw new BadRequestException(
                $this->csen('Stránka nenalezena', 'Page not found'),
                IResponse::S404_NOT_FOUND
            );
        }

        $this->template->event = $event;
        $participants = $this->downloader->download(new ParticipantsRequest((int)$event['eventId']));
        $participants = array_filter($participants, function ($participant) {
            return $participant['status'] == 'participated';
        });
        $this->template->participants = $participants;

        // $this->template->galleryPath = "/media/images/events/" . ($event['eventTypeId'] == 4 ? 'sous-jaro' : 'sous-podzim') . "/rocnik" . ($event['year'] < 10 ? '0' : '') . $event['year'] . "/carousel-photos/";
        $this->template->galleryPath = '';
    }

    public function getEventPhoto(array $event): string
    {
        // choose a photo for the event randomly from the available ones
        return $this->template->basePath . '/media/images/events/event-missing-photo.png';

        // if ($event['year'] < 10) {
        //     $fullYear = '0' . $event['year'];
        // } else {
        //     $fullYear = $event['year'];
        // }

        // $photosBasePath = './media/images/events/' . $eventType . '/rocnik' . $fullYear . '/carousel-photos';
        // $photos = glob($photosBasePath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        // if (empty($photos)) {
        //     return $this->template->basePath . '/media/images/events/event-missing-photo.png';
        // }

        // $photo = $photos[array_rand($photos)];
        // return substr($photo, 1); // remove leading dot
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $events = $this->downloader->download(new EventListRequest(self::TSAF_IDS));

        // sort by date
        usort($events, function ($a, $b) {
            return strtotime($b['begin']) - strtotime($a['begin']);
        });

        foreach ($events as &$event) {
            $event['photo'] = $this->getEventPhoto($event);
        }

        $this->template->events = $events;
    }

    public function eventHasDate(array $event, int $year, int $month): bool
    {
        $eventBegin = strtotime($event['begin']);
        return date('Y', $eventBegin) == $year && date('m', $eventBegin) == $month;
    }
}
