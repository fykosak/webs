<?php

declare(strict_types=1);

namespace App\Modules\Fof\ArchiveModule;

use App\Components\ScheduleList\ScheduleListComponent;
use Exception;

class SchedulePresenter extends BasePresenter
{
    /**
     * Check that at least one template is available for render
     */
    public function isVisible(): bool
    {
        try {
            $this->findTemplateFile();
            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function createComponentScheduleList(): ScheduleListComponent
    {
        return new ScheduleListComponent($this->getContext(), $this->getEvent());
    }
}
