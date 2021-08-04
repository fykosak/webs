<?php

declare(strict_types=1);

namespace App\Modules\ArchiveModule;

use App\Components\Problem\ProblemComponent;
use App\Models\ORM\Problems\DirectoryService;
use Fykosak\Utils\UI\PageTitle;

class ProblemsPresenter extends BasePresenter
{

    private DirectoryService $directoryService;

    public function injectServiceProblem(DirectoryService $directoryService): void
    {
        $this->directoryService = $directoryService;
    }

    public function renderDefault(): void
    {
        $this->setPageTitle(new PageTitle(_('Problems')));

        $this->template->problems = $this->directoryService->findRoot()
            ->findChildByPath('fykos/seminar/34/3')
            ->getProblems(true);
    }

    protected function createComponentProblem(): ProblemComponent
    {
        return new ProblemComponent($this->getContext(), $this->lang);
    }
}
