<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use Fykosak\FKSDBDownloaderCore\Requests\SeriesResultsRequest;

class ResultsPresenter extends BasePresenter
{
    /** @persistent */
    public ?int $year = null;

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $year = $this->year ?? 38;
        $this->template->year = $year;
        $this->template->contest = $this->getContest();
        $this->template->results = $this->downloader->download(new SeriesResultsRequest($this->getContestId(), $year));
    }
}
