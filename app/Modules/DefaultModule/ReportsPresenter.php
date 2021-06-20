<?php

namespace App\Modules\DefaultModule;

class ReportsPresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('Reports'));
        $this->changeViewByLang();
    }

}
