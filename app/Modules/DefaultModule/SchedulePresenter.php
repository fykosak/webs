<?php

namespace App\Modules\DefaultModule;

class SchedulePresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('Schedule of the Competition'));
        $this->changeViewByLang();
    }

}
