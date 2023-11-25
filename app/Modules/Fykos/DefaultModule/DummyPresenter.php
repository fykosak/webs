<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\FKSDBDownloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\SeriesResultsRequest;

class DummyPresenter extends BasePresenter
{
    private FKSDBDownloader $downloader;

    public function injectDownloader(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $this->template->results = json_decode($this->downloader->download(new SeriesResultsRequest(1, 36, 1)), true);
    }
}
