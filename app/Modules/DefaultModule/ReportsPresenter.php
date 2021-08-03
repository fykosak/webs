<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

class ReportsPresenter extends BasePresenter
{

    public function renderDefault(): void
    {
        $this->setPageTitle(_('Reports'));
    }
}
