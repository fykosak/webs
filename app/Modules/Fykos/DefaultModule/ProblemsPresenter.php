<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Problems\ProblemService;
use App\Models\Problems\SeriesService;
use Nette\Utils\DateTime;

class ProblemsPresenter extends BasePresenter
{
    private ProblemService $problemService;
    private SeriesService $seriesService;

    public function injectServiceProblem(ProblemService $problemService, SeriesService $seriesService): void
    {
        $this->problemService = $problemService;
        $this->seriesService = $seriesService;
    }

    public function renderDefault(): void
    {
        $series = $this->seriesService->getLatestSeries('fykos');
        $this->template->series = $series;

        $problems = [];
        foreach ($series->problems as $probNum) {
            $problems[] = $this->problemService->getProblem('fykos', $series->year, $series->series, $probNum);
        }
        $this->template->problems = $problems;

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
