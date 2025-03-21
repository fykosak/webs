<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Components\ImagePreviewModal\ImagePreviewModalComponent;
use App\Components\Problem\ProblemComponent;
use App\Models\Downloader\ProblemService;
use Throwable;

class ProblemsPresenter extends BasePresenter
{
    private readonly ProblemService $problemService;

    /** @persistent */
    public ?int $year = null;
    /** @persistent */
    public ?int $series = null;

    public function injectServiceProblem(ProblemService $problemService): void
    {
        $this->problemService = $problemService;
    }

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $year = $this->year ?? $this->getCurrentYear()->year;
        $series = $this->series ?? $this->problemService->getLatestSeries('fykos', $year);
        $series = $this->problemService->getSeries('fykos', $year, $series);
        $this->template->series = $series;

        $problems = [];
        foreach ($series->problems as $probNum) {
            $problems[] = $this->problemService->getProblem('fykos', $series->year, $series->series, $probNum);
        }
        $this->template->problems = $problems;
        $this->template->problemService = $this->problemService;

        $this->template->yearsAndSeries = $this->getYearsAndSeries();
    }


    private function getYearsAndSeries(): array
    {

        error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

        $yearsAndSeries = [];
        foreach ($this->getContest()->years as $year) {
            try {
                $yearJson = $this->problemService->getYearJson('fykos', $year->year);
                $availableSeriesNumbers = array_keys($yearJson);
                $yearsAndSeries[$year->year] = $availableSeriesNumbers;
            } catch (Throwable $e) {
                continue;
            }
        }

        // sort in decreasing order by key
        krsort($yearsAndSeries);

        return $yearsAndSeries;
    }

    protected function createComponentProblem(): ProblemComponent
    {
        $year = $this->year ?? $this->getCurrentYear()->year;
        $series = $this->series ?? $this->problemService->getLatestSeries('fykos', $year);
        $seriesModel = $this->problemService->getSeries('fykos', $year, $series);
        return new ProblemComponent($this->getContext(), $seriesModel);
    }

    protected function createComponentImagePreviewModal(): ImagePreviewModalComponent
    {
        return new ImagePreviewModalComponent($this->getContext());
    }
}
