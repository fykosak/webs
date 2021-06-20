<?php

namespace App\Modules\ArchiveModule;

class DetailedResultsPresenter extends BasePresenter {

    /**
     * @return void
     * @throws \Exception
     */
    public function renderDefault(): void {
        $this->setPageTitle(_('Detailded results'));
    }
}
