<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use App\Models\Downloader\ProblemService;

class DefaultPresenter extends BasePresenter
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

    public function renderDefault(): void
    {
        $this->template->newsList = $this->loadNews();

        $year = $this->year ?? $this->getCurrentYear()->year;
        $series = $this->series ?? $this->problemService->getLatestSeries('vyfuk', $year);
        $series = $this->problemService->getSeries('vyfuk', $year, $series);
        $this->template->series = $series;

        $previousSeries = $this->problemService->getSeries('vyfuk', $year-1, $this->problemService->getLatestSeries('vyfuk', $year-1)-1);
        $this->template->previousSeries = $previousSeries;

        $this->template->checkAllSolutions = $this->checkAllSolutions($previousSeries, $this->lang);
    }

    public function checkAllSolutions($series, $lang): bool
    {
        $problems = [];
        foreach ($series->problems as $probNum) {
            $problem = $this->problemService->getProblem('vyfuk', $series->year, $series->series, $probNum);
            $problems[] = $problem;
        }

        return array_reduce($problems, function ($carry, $problem) use ($lang) {
            return $carry && $this->problemService->getSolution($problem, $lang) !== null;
        }, true);
    }

    public function loadNews(): array
    {
        $json = file_get_contents(__DIR__ . '/templates/Default/news.json');
        $newsList = json_decode($json, true);

        return $newsList;
    }
}
