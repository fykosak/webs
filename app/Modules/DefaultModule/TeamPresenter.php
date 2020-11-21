<?php

namespace App\Modules\DefaultModule;

use App\Modules\PublicModule\BasePresenter;
use App\Components\TeamListComponent;

class TeamPresenter extends BasePresenter {

    /**
     * @return void
     * @throws \Exception
     */
    public function renderList(): void {
        $this->setPageTitle(_('Seznam týmů'));
    }

    protected function createComponentTeamList(): TeamListComponent {
        return new TeamListComponent($this->getContext(), 150);
    }
}
