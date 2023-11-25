<?php

declare(strict_types=1);

namespace App\Modules\Fol\ArchiveModule;

use App\Components\Map\MapComponent;
use Nette\Application\BadRequestException;

class DefaultPresenter extends BasePresenter
{
    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    protected function createComponentMap(): MapComponent
    {
        return new MapComponent($this->getContext(), $this->gamePhaseCalculator, $this->getEvent());
    }
}
