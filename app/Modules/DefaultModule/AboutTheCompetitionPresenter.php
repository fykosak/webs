<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

class AboutTheCompetitionPresenter extends BasePresenter
{

    public function renderDefault(): void
    {
        $this->setPagetitle(_('About the Competition'));
        $this->changeViewByLang();
    }
}
