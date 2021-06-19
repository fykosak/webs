<?php

namespace App\Modules\DefaultModule;
use \App\Models\ORM\FaqService;

class HowToPlayPresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('How to play'));
        $this->changeViewByLang();
    }

}
