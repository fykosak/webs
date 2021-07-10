<?php

namespace App\Modules\ArchiveModule;

use App\Models\ORM\Problems\ProblemService;

class TasksPresenter extends BasePresenter {

    private ProblemService $problemService;

    public function injectServiceProblem(ProblemService $problemService): void {
        $this->problemService = $problemService;
    }

    public function renderDefault(): void {
        $this->template->problems = $this->problemService->getTable()->where('directory_id', 1);
    }
}
