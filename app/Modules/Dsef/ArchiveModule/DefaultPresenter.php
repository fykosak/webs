<?php

declare(strict_types=1);

namespace App\Modules\Dsef\ArchiveModule;

use App\Components\ImageGallery\ImageGalleryControl;
use App\Models\Downloader\FKSDBDownloader;
use App\Models\Downloader\ScheduleRequest;

class DefaultPresenter extends BasePresenter
{
    private readonly FKSDBDownloader $downloader;

    final public function inject(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentGallery(): ImageGalleryControl
    {
        return new ImageGalleryControl($this->getContext());
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $data = $this->downloader->download(new ScheduleRequest($this->getEvent()->eventId, ['excursion']));
        $this->template->data = $data;
    }
}
