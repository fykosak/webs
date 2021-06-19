<?php

namespace App\Modules\DefaultModule;

class ReportPresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('Reports'));
        $this->changeViewByLang();
    }

}
