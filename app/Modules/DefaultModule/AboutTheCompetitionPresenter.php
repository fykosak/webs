<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

class AboutTheCompetitionPresenter extends BasePresenter
{

    public function renderDefault(): void
    {
        $this->setPageTitle(_('About the Competition'));
    }
}
