<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use Fykosak\FKSDBDownloaderCore\Requests\SeriesResultsRequest;
use Fykosak\NetteFKSDBDownloader\NetteFKSDBDownloader;

class DummyPresenter extends BasePresenter
{
    private NetteFKSDBDownloader $downloader;

    public function injectDownloader(NetteFKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    public function renderDefault(): void
    {
        $this->template->results = json_decode($this->downloader->download(new SeriesResultsRequest(1, 35, 1)), true);
    }
}
