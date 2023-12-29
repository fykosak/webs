<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\FKSDBDownloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\SeriesResultsRequest;

class ResultsPresenter extends BasePresenter
{
    /** @persistent */
    public ?int $year = self::CURRENT_YEAR;
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
        $year = $this->year ?? self::CURRENT_YEAR;
        $this->template->year = $year;
        $this->template->results = json_decode(
            $this->downloader->download(new SeriesResultsRequest(1, $year, 1)),
            true
        );
    }
}
