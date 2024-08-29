<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Components\Problem\Problem;
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
        $year = $this->year ?? self::CURRENT_YEAR;
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
        for ($year = self::CURRENT_YEAR; $year > 0; $year--) {
            try {
                $yearJson = $this->problemService->getYearJson('fykos', $year);
                $availableSeriesNumbers = array_keys($yearJson);
                $yearsAndSeries[$year] = $availableSeriesNumbers;
            } catch (Throwable $e) {
                continue;
            }
        }
        return $yearsAndSeries;
    }

    protected function createComponentProblem(): Problem
    {
        $year = $this->year ?? self::CURRENT_YEAR;
        $series = $this->series ?? $this->problemService->getLatestSeries('fykos', $year);
        $seriesModel = $this->problemService->getSeries('fykos', $year, $series);
        return new Problem($this->getContext(), $seriesModel);
    }
}
