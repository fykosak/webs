<?php

namespace App\Modules\ArchiveModule;

use App\Components\TeamList\TeamListComponent;
use Nette\Application\BadRequestException;

class TeamsPresenter extends BasePresenter {

    /**
     * @return void
     * @throws \Exception
     */
    public function renderDefault(): void {
        $this->setPageTitle(_('Teams'));
    }

    /**
     * @return TeamListComponent
     * @throws BadRequestException
     * @throws \Throwable
     */
    protected function createComponentTeamList(): TeamListComponent {
        return new TeamListComponent($this->getContext(), $this->getEvent()->eventId);
    }
}
