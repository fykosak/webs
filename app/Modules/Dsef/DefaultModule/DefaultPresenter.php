<?php

declare(strict_types=1);

namespace App\Modules\Dsef\DefaultModule;

use App\Components\PersonSchedule\AllScheduleListComponent;
use App\Components\UpperHomePrague\UpperHomePrague;

class DefaultPresenter extends BasePresenter
{
    protected function createComponentPrague(): UpperHomePrague
    {
        return new UpperHomePrague($this->getContext());
    }

    protected function createComponentTestovaciaKokotina(): AllScheduleListComponent
    {
        return new AllScheduleListComponent(163, $this->getContext());
    }
}
