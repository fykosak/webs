<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Downloader\ProblemService;
use App\Models\Downloader\SeriesService;
use Nette\Utils\DateTime;

class ProblemsPresenter extends BasePresenter
{
    private ProblemService $problemService;

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

        $this->template->problemIcons = [
            1 => 'fas fa-smile',
            2 => 'fas fa-smile',
            3 => 'fas fa-brain',
            4 => 'fas fa-brain',
            5 => 'fas fa-brain',
            6 => 'fas fa-lightbulb',
            7 => 'fas fa-flask',
            8 => 'fas fa-book'
        ];
    }
}
