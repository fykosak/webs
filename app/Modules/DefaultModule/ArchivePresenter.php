<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use App\Components\EventList\EventListComponent;

class ArchivePresenter extends BasePresenter
{
    protected function createComponentEventList(): EventListComponent
    {
        return new EventListComponent($this->getContext());
    }
}
