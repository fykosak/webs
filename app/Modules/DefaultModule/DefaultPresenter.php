<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use App\Components\UpperHomeBeforeRegistration\UpperHomeBeforeRegistrationComponent;
use App\Components\UpperHomeMap\UpperHomeMapComponent;

class DefaultPresenter extends BasePresenter
{
    protected function createComponentUpperHomeMap(): UpperHomeMapComponent
    {
        return new UpperHomeMapComponent($this->getContext(), $this->gamePhaseCalculator);
    }

    protected function createComponentUpperHomeBeforeRegistration(): UpperHomeBeforeRegistrationComponent
    {
        return new UpperHomeBeforeRegistrationComponent($this->gamePhaseCalculator);
    }
}
