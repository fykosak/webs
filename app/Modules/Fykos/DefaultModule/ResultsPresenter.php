<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\FKSDBDownloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\SeriesResultsRequest;

class ResultsPresenter extends BasePresenter
{

    private FKSDBDownloader $downloader;

    private int $FYKOSYear = 36; // TODO: get from URL

    public function injectDownloader(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    public function actionDefault(int $year = null): void
    {
        if ($year !== null) {
            $this->FYKOSYear = $year;
        } else {
            $this->FYKOSYear = $this->currentFYKOSYear;
        }
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $this->template->FYKOSYear = $this->FYKOSYear;
        $this->template->currentFYKOSYear = $this->currentFYKOSYear;

        $this->template->results = json_decode($this->downloader->download(new SeriesResultsRequest(1, $this->FYKOSYear, 1)), true);
    }
}
