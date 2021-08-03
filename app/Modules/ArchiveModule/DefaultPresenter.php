<?php

declare(strict_types=1);

namespace App\Modules\ArchiveModule;

class DefaultPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->setPageTitle(_('Archive year home page ...'));
    }
}
