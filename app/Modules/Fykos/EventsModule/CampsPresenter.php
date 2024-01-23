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
        } elseif ($event['eventTypeId'] == 5) {
            $year = 1986 + $event['year'];
            return [
                'cs' => 'Podzimní soustředění ' . $year,
                'en' => 'Autumn camp ' . $year,
            ];
        } else {
            return [
                'cs' => '',
                'en' => '',
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
        $data = $this->downloader->download(new EventRequest((int)$id));
        if (!in_array($data['eventTypeId'], self::CAMPS_IDS)) {
            throw new ForbiddenRequestException();
        }
        $this->template->data = $data;
        $this->template->participants = $this->downloader->download(new ParticipantsRequest((int)$id));
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $data = $this->downloader->download(new EventListRequest(self::CAMPS_IDS));

        // sort by date
        usort($data, function ($a, $b) {
            return strtotime($b['begin']) - strtotime($a['begin']);
        });

        foreach ($data as &$event) {
            $event['heading'] = $this->getEventHeading($event);
        }

        $this->template->data = $data;

        // Debugger::dump($data); // Display data in Nette Debugger

        // foreach ($this->template->camps as &$camp) {
        //     $camp['link'] = 'https://fykos.cz/rocnik' . $camp['year'] . '/sous-' . $camp['season'] . '/start';
        // }
    }
}
