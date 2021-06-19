<?php

namespace App\Modules\DefaultModule;

class RulesPresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('Brief Rules'));
        $this->changeViewByLang();
    }

    public function renderComplete(){
        $this->setPagetitle(_('Full Rules'));
        $this->changeViewByLang();
    }

    public function renderOrganisationalRegulations(){
        $this->setPagetitle(_('Organisational Regulations'));
        $this->changeViewByLang();
    }
}
