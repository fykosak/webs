<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Components\ImagePreviewModal\ImagePreviewModalComponent;
use App\Components\Problem\ProblemComponent;
use App\Models\Downloader\Models\ProblemManager\SeriesModel;
use App\Models\Downloader\Services\FileService;
use App\Models\Downloader\Services\ProblemService;
use Throwable;

class ProblemsPresenter extends BasePresenter
{
    private readonly ProblemService $problemService;
    private readonly FileService $fileService;

    /** @persistent */
    public ?int $year = null;
    /** @persistent */
    public ?int $series = null;

    public function injectServiceProblem(ProblemService $problemService, FileService $fileService): void
    {
        $this->problemService = $problemService;
        $this->fileService = $fileService;
    }

    private function getSeries(): SeriesModel
    {
        $year = $this->year ?? $this->getCurrentYear()->year;
        $seriesId = $this->series
            ? $this->problemService->getSeriesId(ProblemService::FYKOS, $year, (string)$this->series)
            : $this->problemService->getLatestSeriesId(ProblemService::FYKOS);

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

        $this->template->fileService = $this->fileService;
        $this->template->yearsAndSeries = $this->problemService->getYears(ProblemService::FYKOS);
        $this->template->currentContestYear = $this->problemService->getYear(ProblemService::FYKOS, $this->year ?? $this->getCurrentYear()->year);
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
