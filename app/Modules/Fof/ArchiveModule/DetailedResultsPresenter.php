<?php

declare(strict_types=1);

namespace App\Modules\Fof\ArchiveModule;

use Fykosak\Utils\UI\Title;

class DetailedResultsPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->setPageTitle(new Title(null, $this->csen('Podrobné výsledky', 'Detailed results')));
    }
}
