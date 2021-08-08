<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use App\Components\EventList\EventListComponent;
use Fykosak\Utils\UI\PageTitle;

class ArchivePresenter extends BasePresenter
{

    public function renderDefault(): void
    {
        $this->setPageTitle(new PageTitle(_('archive.title')));
    }

    protected function createComponentEventList(): EventListComponent
    {
        return new EventListComponent($this->getContext());
    }
}
