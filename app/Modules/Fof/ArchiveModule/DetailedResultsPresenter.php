<?php

declare(strict_types=1);

namespace App\Modules\Fof\ArchiveModule;

use Fykosak\Utils\UI\PageTitle;

class DetailedResultsPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->setPageTitle(new PageTitle($this->csen('Podrobné výsledky', 'Detailed results')));
    }
}
