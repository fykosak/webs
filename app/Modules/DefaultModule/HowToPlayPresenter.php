<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

class HowToPlayPresenter extends BasePresenter
{

    public function renderDefault(): void
    {
        $this->setPageTitle(_('How to play'));
    }
}
