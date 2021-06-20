<?php

namespace App\Modules\DefaultModule;

use \App\Models\ORM\FaqService;
use App\Components\EventList\EventListComponent;

class ArchivePresenter extends BasePresenter {

    public function renderDefault(): void
    {
        $this->setPagetitle(_('Archive'));
        $this->changeViewByLang();
    }
    
    protected function createComponentEventList(): EventListComponent {
        return new EventListComponent($this->getContext());
    }
    
}
