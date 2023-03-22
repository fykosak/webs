<?php

declare(strict_types=1);

namespace App\Modules\Fol\ArchiveModule;

use App\Components\Map\MapComponent;

class DefaultPresenter extends BasePresenter
{
    protected function createComponentMap(): MapComponent
    {
        return new MapComponent($this->getContext(), $this->gamePhaseCalculator, $this->getEvent());
    }
}
