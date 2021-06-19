<?php

namespace App\Modules\DefaultModule;
use \App\Models\ORM\FaqService;

class AboutTheCompetitionPresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('About the Competition'));
        $this->changeViewByLang();
    }

}
