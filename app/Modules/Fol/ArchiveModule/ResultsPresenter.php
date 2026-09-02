<?php

declare(strict_types=1);

namespace App\Modules\Fol\ArchiveModule;

use App\Components\TeamResults\TeamResultsComponent;
use Fykosak\Utils\UI\Title;
use Nette\Application\BadRequestException;

class ResultsPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->setPageTitle(new Title(null, $this->csen('Výsledky', 'Results')));
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    protected function createComponentTeamResults(): TeamResultsComponent
    {
        return new TeamResultsComponent($this->getContext(), $this->getEvent());
    }
}
