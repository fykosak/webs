<?php

namespace App\Modules\ArchiveModule;

use App\Components\TeamList\TeamListComponent;
use App\Components\TeamResults\TeamResultsComponent;

class ArchivePresenter extends BasePresenter {

    protected function createComponentTeamList(): TeamListComponent {
        return new TeamListComponent($this->getContext(), $this->getEvent()->eventId);
    }

    protected function createComponentTeamResults(): TeamResultsComponent {
        return new TeamResultsComponent($this->getContext(), $this->getEvent()->eventId);
    }
}
