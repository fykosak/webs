<?php

namespace App\Modules\DefaultModule;
use \App\Models\ORM\FaqService;

class ArchivePresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('Previous years'));
        $this->changeViewByLang();
    }

}
