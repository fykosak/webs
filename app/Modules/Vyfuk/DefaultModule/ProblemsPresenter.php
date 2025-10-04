<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use App\Components\ImagePreviewModal\ImagePreviewModalComponent;
use App\Components\Problem\ProblemComponent;
use App\Models\Downloader\Models\ProblemManager\SeriesModel;
use App\Models\Downloader\Services\ProblemService;
use App\Models\Downloader\Services\FileService;
use Throwable;

class ProblemsPresenter extends BasePresenter
{
    private readonly FileService $fileService;
    private readonly ProblemService $problemService;

    /** @persistent */
    public ?int $year = null;
    /** @persistent */
    public ?int $series = null;

    public function injectServiceProblem(FileService $fileService, ProblemService $problemService): void
    {
        $this->fileService = $fileService;
        $this->problemService = $problemService;
    }

    private function getSeries(): SeriesModel
    {
        $seriesId = $this->year && $this->series
            ? $this->problemService->getSeriesId(ProblemService::VYFUK, $this->year, (string)$this->series)
            : $this->problemService->getLatestSeriesId(ProblemService::VYFUK);

        return $this->problemService->getSeries($seriesId);
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $series = $this->getSeries();
        $this->template->series = $series;
        $this->template->problems = $series->problems;

        $this->template->currentContestYear = $this->problemService->getYear(ProblemService::VYFUK, $series->contestYear['year']);
        $this->template->fileService = $this->fileService;

        $yearsAndSeries = $this->problemService->getYears(ProblemService::VYFUK);
        usort($yearsAndSeries, function ($a, $b) {
            return $b->year <=> $a->year;
        });
        $this->template->yearsAndSeries = $yearsAndSeries;
    }

    protected function createComponentProblem(): ProblemComponent
    {
        return new ProblemComponent($this->getContext(), $this->getSeries());
    }

    protected function createComponentImagePreviewModal(): ImagePreviewModalComponent
    {
        return new ImagePreviewModalComponent($this->getContext());
    }
}
