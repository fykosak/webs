<?php

namespace App\Modules\ArchiveModule;

use App\Components\TeamResults\TeamResultsComponent;

class ResultsPresenter extends BasePresenter {
    
    /**
     * @return void
     * @throws \Exception
     */
    public function renderList(): void {
        $this->setPageTitle(_('Team results'));
    }

    protected function createComponentTeamResults(): TeamResultsComponent {
        return new TeamResultsComponent($this->getContext(), $this->getEvent()->eventId);
    }

}
