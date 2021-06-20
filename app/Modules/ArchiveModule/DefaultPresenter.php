<?php

namespace App\Modules\ArchiveModule;

class DefaultPresenter extends BasePresenter {
    /**
     * @return void
     * @throws \Exception
     */
    public function renderDefault(): void {
        $this->setPageTitle(_('Archive year home page ...'));
    }
}
