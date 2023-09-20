<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Models\Problems\ProblemService;

class TaskPresenter extends BasePresenter
{
    private ProblemService $problemService;

    public function injectServiceProblem(ProblemService $problemService): void
    {
        $this->problemService = $problemService;
    }

    public function renderDefault(): void
    {
        $problems = [];
        for ($i = 1; $i <= 8; $i++) {
            $problems[] = $this->problemService->getProblem('fykos', 37, 1, $i);
        }
        $this->template->problems = $problems;
    }
}
