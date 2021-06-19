<?php

namespace App\Modules\DefaultModule;
use \App\Models\ORM\FaqService;

class RulesPresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('Rules'));
        $this->changeViewByLang();
    }

    public function renderComplete(){
        $this->setPagetitle(_('Full Rules'));
        $this->changeViewByLang();
    }
}
