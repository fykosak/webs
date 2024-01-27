<?php

declare(strict_types=1);

namespace App\Modules\Fykos\EventsModule;

use App\Models\Downloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\EventListRequest;
use Fykosak\FKSDBDownloaderCore\Requests\EventRequest;
use Fykosak\FKSDBDownloaderCore\Requests\ParticipantsRequest;
use Nette\Application\ForbiddenRequestException;

class CampsPresenter extends BasePresenter
{
    private const CAMPS_IDS = [4, 5];

    private FKSDBDownloader $downloader;

    public function inject(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    public function getEventHeading($event): array
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

    /**
     * @throws ForbiddenRequestException
     * @throws \Throwable
     */
    public function renderDetail(): void
    {
        $id = $this->getParameter('id');
        $events = $this->downloader->download(new EventRequest((int)$id));
        if (!in_array($events['eventTypeId'], self::CAMPS_IDS)) {
            throw new ForbiddenRequestException();
        }
        $this->template->events = $events;
        $this->template->participants = $this->downloader->download(new ParticipantsRequest((int)$id));
    }

    public function get_event_photo($event): string
    {
        if ($event['eventTypeId'] == 4) {
            $event_type_str = 'sous-jaro';
        } else { // $event['eventTypeId'] == 5
            $event_type_str = 'sous-podzim';
        }
        if ($event['year'] < 10) {
            $stryear = '0' . $event['year'];
        } else {
            $stryear = $event['year'];
        }

        $photosBasePath = './images/events/' . $event_type_str . '/rocnik' . $stryear . '/carousel-photos';
        $photos = glob($photosBasePath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        
        if (empty($photos)) {
            return $this->template->basePath .'/images/events/event-missing-photo.png';
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
            $event['photo'] = $this->get_event_photo($event);
        }

        $this->template->events = $events;
    }
}
