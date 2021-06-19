<?php

namespace App\Modules\DefaultModule;

class AboutTheCompetitionPresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('About the Competition'));
        $this->changeViewByLang();
    }

}
