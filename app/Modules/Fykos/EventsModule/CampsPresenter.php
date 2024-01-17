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
        usort($data, function($a, $b) {
            return strtotime($b['begin']) - strtotime($a['begin']);
        });

        $this->template->data = $data;

        // Debugger::dump($data); // Display data in Nette Debugger

        // foreach ($this->template->camps as &$camp) {
        //     $camp['link'] = 'https://fykos.cz/rocnik' . $camp['year'] . '/sous-' . $camp['season'] . '/start';
        // }
    }
}
