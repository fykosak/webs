<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use App\Components\ImagePreviewModal\ImagePreviewModalComponent;
use App\Components\Problem\ProblemComponent;
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

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $year = $this->year ?? $this->getCurrentYear()->year;
        $seriesNumber = $this->series ?? $this->problemService->getLatestSeries('vyfuk', $year);

        $seriesId = $this->problemService->getSeriesId(ProblemService::VYFUK, $year, (string)$seriesNumber);
        $seriesModel = $this->problemService->getSeries($seriesId);

        $this->template->series = $seriesModel;
        $this->template->currentContestYear = $this->problemService->getYear(ProblemService::VYFUK, $year);
        $this->template->problems = $seriesModel->problems;

        $this->template->fileService = $this->fileService;

        $this->template->yearsAndSeries = $this->problemService->getYears(ProblemService::VYFUK);
    }

    protected function createComponentProblem(): ProblemComponent
    {
        $year = $this->year ?? $this->getCurrentYear()->year;
        $seriesNumber = $this->series ?? $this->problemService->getLatestSeries(ProblemService::VYFUK);
        $seriesId = $this->problemService->getSeriesId(4, $year, (string)$seriesNumber);
        $seriesModel = $this->problemService->getSeries($seriesId);
        return new ProblemComponent($this->getContext(), $seriesModel);
    }

    protected function createComponentImagePreviewModal(): ImagePreviewModalComponent
    {
        return new ImagePreviewModalComponent($this->getContext());
    }
}
