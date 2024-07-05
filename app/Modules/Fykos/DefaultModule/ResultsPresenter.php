<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\FKSDBDownloader;
use Fykosak\FKSDBDownloaderCore\Requests\SeriesResultsRequest;

use Nette\Application\Attributes\Persistent;
use Tracy\Debugger;

class ResultsPresenter extends BasePresenter
{
    #[Persistent]
    public ?int $year;


    private readonly FKSDBDownloader $downloader;

    public function injectDownloader(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        Debugger::barDump($this->year);

        $year = $this->year ?? self::CURRENT_YEAR;
        $this->template->year = $year;
        $this->template->results = $this->downloader->download(new SeriesResultsRequest(1, $year, 1));
    }
}
