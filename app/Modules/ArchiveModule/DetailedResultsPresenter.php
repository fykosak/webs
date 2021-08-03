<?php

declare(strict_types=1);

namespace App\Modules\ArchiveModule;

class DetailedResultsPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->setPageTitle(_('Detailed results'));
    }
}
