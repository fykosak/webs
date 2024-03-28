<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use App\Models\Downloader\EventService;
use App\Models\Downloader\FKSDBDownloader;
use Exception;
use Nette\Application\Responses\CallbackResponse;
use Nette\Caching\Cache;
use Nette\Caching\Storage;

class EventsPresenter extends BasePresenter
{
    protected Cache $cache;
    protected FKSDBDownloader $DBDownloader;
    protected EventService $eventService;

    public function injectEventServicesAndCache(Storage $storage, FKSDBDownloader $downloader, EventService $eventService): void
    {
        $this->cache = new Cache($storage);
        $this->DBDownloader = $downloader;
        $this->eventService = $eventService;
    }

    public function renderMeeting(): void
    {
        $events = $this->eventService->getEvents([11, 12]);
        $this->template->events = array_reverse($events);
    }

    public function renderCamp(): void
    {
        $events = $this->eventService->getEvents([10]);
        $this->eventService->removeByIDs($events, [147]);
        $this->template->events = array_reverse($events);
    }

    public function actionGetRawCalendar(): void
    {
        $calendar = $this->loadCalendar();
        if (!$calendar) {
            $this->error('', 503);
        } else {
            $this->sendResponse(new CallbackResponse(function ($request, $response) use ($calendar) {
                $response->setHeader("Content-Type", "text/calendar");
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
            "vyfuk.calender",
            function (&$dependencies): string {
                $dependencies[Cache::EXPIRE] = time() + 24 * 60 * 60;
                $curl = curl_init("https://drive.vyfuk.org/remote.php/dav/public-calendars/tLnfCNLzypBHHeEb?export");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $res = curl_exec($curl);
                curl_close($curl);
                if (curl_getinfo($curl)['http_code'] != 200) {
                    throw new Exception("Vyfuk calendar failed to be downloaded");
                }
                return $res;
            }
        );
    }
}
