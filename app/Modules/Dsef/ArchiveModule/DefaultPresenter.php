<?php

declare(strict_types=1);

namespace App\Modules\Dsef\ArchiveModule;

use App\Components\ImageGallery\ImageGalleryControl;
use App\Models\Downloader\Downloaders\FKSDBDownloader;
use App\Models\Downloader\Requests\ScheduleRequest;
use App\Models\Images\ImageService;

class DefaultPresenter extends BasePresenter
{
    private readonly FKSDBDownloader $downloader;
    private readonly ImageService $imageService;

    final public function inject(FKSDBDownloader $downloader, ImageService $imageService): void
    {
        $this->downloader = $downloader;
        $this->imageService = $imageService;
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
        $this->template->hasPhotos = $this->imageService->hasPhotosEvent($this->getEvent());
        $this->template->data = $data;
    }
}
