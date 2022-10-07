<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use App\Components\UpperHomeBeforeRegistration\UpperHomeBeforeRegistrationComponent;
use App\Components\UpperHomePrague\UpperHomePrague;

class DefaultPresenter extends BasePresenter
{
    protected function createComponentPrague(): UpperHomePrague
    {
        return new UpperHomePrague($this->getContext());
    }

    protected function createComponentUpperHomeBeforeRegistration(): UpperHomeBeforeRegistrationComponent
    {
        return new UpperHomeBeforeRegistrationComponent($this->gamePhaseCalculator);
    }
}
