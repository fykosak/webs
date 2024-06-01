<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use App\Components\ImageGallery\ImageGalleryControl;
use App\Components\PdfGallery\PdfGalleryControl;
use App\Models\Downloader\EventService;
use DateTime;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Application\Responses\CallbackResponse;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

class EventsPresenter extends BasePresenter
{
    protected Cache $cache;
    protected EventService $eventService;

    public function injectEventServicesAndCache(Storage $storage, EventService $eventService): void
    {
        $this->cache = new Cache($storage);
        $this->eventService = $eventService;
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentGallery(): ImageGalleryControl
    {
        return new ImageGalleryControl($this->getContext());
    }

    protected function createComponentPdfGallery(): PdfGalleryControl
    {
        return new PdfGalleryControl($this->getContext());
    }

    public function renderDetail(int $event): void
    {
        $event = $this->eventService->getEvent($event);
        if ($event->contestId != 2) {
            $this->error();
        }
        $this->template->event = $event;
        $this->template->galery = $this->createComponentGallery()->hasPhotos("/photos/event/" . $event->eventId);
        $this->template->pdf = $this->createComponentPdfGallery()->hasFiles("/download/event/" . $event->eventId);
        $persons = $event->end < new DateTime() ? $this->eventService->getEventOrganizers($event->eventId) : array();
        $array = array();
        foreach ($persons as $person) {
            $array[] = $person->person->name;
        }
        $this->template->organizers = implode(', ', $array);
        $persons = $event->end < new DateTime() ? $this->eventService->getEventParticipants($event->eventId) : array();
        $array = array();
        foreach ($persons as $person) {
            $array[] = $person->name;
        }
        $this->template->participants = implode(', ', $array);
    }

    public function renderDefault(): void
    {
        $events = $this->eventService->getEvents([10, 11, 12, 15]);
        $this->template->events = array_reverse($events);
    }

    /**
     * @throws AbortException
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function actionGetRawCalendar(): void
    {
        $calendar = $this->loadCalendar();
        if (!$calendar) {
            $this->error('', 503);
        } else {
            $this->sendResponse(new CallbackResponse(function ($request, $response) use ($calendar) {
                $response->setHeader('Content-Type', 'text/calendar');
                echo ($calendar);
            }));
        }
    }

    /**
     * @throws \Throwable
     */
    private function loadCalendar(): string
    {
        return $this->cache->load(
            'vyfuk.calender',
            function (&$dependencies): string {
                $dependencies[Cache::Expire] = time() + 24 * 60 * 60;
                $curl = curl_init('https://drive.vyfuk.org/remote.php/dav/public-calendars/tLnfCNLzypBHHeEb?export');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $res = curl_exec($curl);
                curl_close($curl);
                if (curl_getinfo($curl)['http_code'] != 200) {
                    throw new Exception('Vyfuk calendar failed to be downloaded');
                }
                return $res;
            }
        );
    }
}
