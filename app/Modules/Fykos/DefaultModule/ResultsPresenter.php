<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use Fykosak\FKSDBDownloaderCore\Requests\SeriesResultsRequest;
use Nette\Application\BadRequestException;
use Nette\Http\IResponse;

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
        $this->template->results = $this->downloader->download(new SeriesResultsRequest(1, $year));
        // HACK BEFORE BODY-READY IS IMPLEMENTED
        $this->template->results = $this->removeSeriesFromResults(2, $this->template->results);
    }

    public function removeSeriesFromResults(int $seriesId, array $results): array
    {
        foreach (['FYKOS_1', 'FYKOS_2', 'FYKOS_3', 'FYKOS_4'] as $key) {
            if (isset($results['tasks'][$key][$seriesId])) {
                unset($results['tasks'][$key][$seriesId]);
            }
        }
        return $results;
    }
}
