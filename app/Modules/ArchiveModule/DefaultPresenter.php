<?php

declare(strict_types=1);

namespace App\Modules\ArchiveModule;

use Fykosak\Utils\UI\PageTitle;

class DefaultPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->setPageTitle(new PageTitle(_('Archive year home page ...')));
    }
}
