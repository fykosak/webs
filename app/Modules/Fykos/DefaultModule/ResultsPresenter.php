<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use Fykosak\FKSDBDownloaderCore\Requests\SeriesResultsRequest;
use Nette\Application\Attributes\Persistent;

class ResultsPresenter extends BasePresenter
{
    #[Persistent]
    public ?int $year = null;

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $year = $this->year ?? $this->getCurrentYear()->year;
        $this->template->year = $year;
        $this->template->contest = $this->getContest();
        $this->template->results = $this->downloader->download(new SeriesResultsRequest($this->getContestId(), $year));
    }
}
