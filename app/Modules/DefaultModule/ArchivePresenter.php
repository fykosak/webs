<?php

namespace App\Modules\DefaultModule;

class ArchivePresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('Competition archive'));
        $this->changeViewByLang();
    }

}
