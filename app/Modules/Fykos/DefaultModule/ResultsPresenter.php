<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\FKSDBDownloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\SeriesResultsRequest;

class ResultsPresenter extends BasePresenter
{
    /** @persistent */
    public ?int $year = self::CURRENT;
    private FKSDBDownloader $downloader;

    private const CURRENT = 37;

    public function injectDownloader(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $year = $this->year ?? self::CURRENT;
        $this->template->FYKOSYear = $year;
        $this->template->currentFYKOSYear = $this->currentFYKOSYear;

        $this->template->results = json_decode(
            $this->downloader->download(new SeriesResultsRequest(1, $year, 1)),
            true
        );
    }
}
