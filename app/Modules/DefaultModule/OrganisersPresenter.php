<?php

namespace App\Modules\DefaultModule;

class OrganisersPresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('List of Organisers'));
        $this->changeViewByLang();
    }

}
