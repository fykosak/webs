<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use App\Components\ScheduleList\ScheduleListComponent;

final class SchedulePresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    public function createComponentScheduleList(): ScheduleListComponent
    {
        return new ScheduleListComponent($this->getContext(), $this->getNewestEvent());
    }
}
