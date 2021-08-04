<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use Fykosak\Utils\UI\PageTitle;

class SchedulePresenter extends BasePresenter
{

    public function renderDefault(): void
    {
        $this->setPageTitle(new PageTitle(_('Schedule of the Competition')));
    }
}
