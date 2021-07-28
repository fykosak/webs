<?php

namespace App\Modules\ArchiveModule;

use App\Components\Problem\ProblemComponent;
use App\Models\ORM\Problems\DirectoryService;
use App\Models\ORM\Problems\ProblemModel;

class TasksPresenter extends BasePresenter
{

    private DirectoryService $directoryService;

    public function injectServiceProblem(DirectoryService $directoryService): void
    {
        $this->directoryService = $directoryService;
    }

    public function renderDefault(): void
    {
        $this->setPageTitle(_('Tasks'));
        $problems = [];
        foreach ($this->directoryService->findByPrimary(15)->getProblems() as $row) {
            $problems[] = ProblemModel::createFromActiveRow($row);
        }
        $this->template->problems = $problems;
    }

    protected function createComponentProblem(): ProblemComponent
    {
        return new ProblemComponent($this->getContext(), $this->lang);
    }
}
