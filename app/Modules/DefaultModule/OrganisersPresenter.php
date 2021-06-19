<?php

namespace App\Modules\DefaultModule;
use \App\Models\ORM\FaqService;

class OrganisersPresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('List of Organisers'));
        $this->changeViewByLang();
    }

}
