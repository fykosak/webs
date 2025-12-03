<?php

declare(strict_types=1);

namespace App\Modules\Dsef\DefaultModule;

use App\Models\Downloader\Downloaders\FKSDBDownloader;
use App\Models\Downloader\Models\EventModel;
use App\Models\Downloader\Requests\ScheduleRequest;
use Nette\Application\BadRequestException;

class CurrentPresenter extends BasePresenter
{
    private readonly FKSDBDownloader $downloader;

    final public function inject(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }
    /**
     * @throws \Throwable
     */
    public function isVisible(): bool
    {
        return !$this->getNewestEvent()->isLongAfterTheEvent();
    }

    /**
     * @throws BadRequestException|\Throwable
     */
    public function actionDefault(): void
    {
        if (!self::isVisible()) {
            $this->error();
        }
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $data = $this->downloader->download(new ScheduleRequest($this->getNewestEvent()->eventId, ['excursion']));
        $filteredData = [];
        foreach ($data as $datum) {
            if (in_array($datum['groupId'], [245, 246], true)) {
                $filteredData[$datum['groupId']] = $datum;
            }
        }
        $this->template->data = $filteredData;
    }
}
