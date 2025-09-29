<?php

declare(strict_types=1);

namespace App\Modules\Fol\ArchiveModule;

use App\Components\TeamList\TeamListComponent;
use Fykosak\Utils\UI\PageTitle;
use Nette\Application\BadRequestException;

class TeamsPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->setPageTitle(new PageTitle($this->csen('Týmy', 'Teams')));
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    protected function createComponentTeamList(): TeamListComponent
    {
        return new TeamListComponent($this->getContext(), $this->getEvent()->eventId);
    }
}
