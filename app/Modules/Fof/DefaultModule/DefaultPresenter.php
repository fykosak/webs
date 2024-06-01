<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use App\Components\ResultsPanel\ResultsPanelComponent;
use App\Components\UpperHomeBeforeRegistration\UpperHomeBeforeRegistrationComponent;
use App\Components\UpperHomePrague\UpperHomePrague;

class DefaultPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    protected function createComponentPrague(): UpperHomePrague
    {
        return new UpperHomePrague($this->getContext(), $this->getNewestEvent());
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentUpperHomeBeforeRegistration(): UpperHomeBeforeRegistrationComponent
    {
        return new UpperHomeBeforeRegistrationComponent($this->getContext(), $this->getNewestEvent());
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentResultsPanel(): ResultsPanelComponent
    {
        return new ResultsPanelComponent($this->getContext(), $this->getNewestEvent());
    }
}
