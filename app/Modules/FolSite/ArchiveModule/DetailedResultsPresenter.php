<?php

declare(strict_types=1);

namespace App\Modules\FolSite\ArchiveModule;

use Fykosak\Utils\UI\PageTitle;

class DetailedResultsPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->setPageTitle(new PageTitle(_('Detailed results')));
    }
}
