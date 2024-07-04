<?php

declare(strict_types=1);

namespace App\Modules\Fol\DefaultModule;

use App\Components\Countdown\CountdownComponent;
use App\Components\ResultsPanel\ResultsPanelComponent;
use App\Components\UpperHomeBeforeRegistration\UpperHomeBeforeRegistrationComponent;
use App\Components\UpperHomeMap\UpperHomeMapComponent;

class DefaultPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    protected function createComponentUpperHomeMap(): UpperHomeMapComponent
    {
        return new UpperHomeMapComponent(
            $this->getContext(),
            $this->getNewestEvent()
        );
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
    protected function createComponentCountdown(): CountdownComponent
    {
        return new CountdownComponent($this->getContext(), $this->getNewestEvent()->game->begin);
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentResultsPanel(): ResultsPanelComponent
    {
        return new ResultsPanelComponent($this->getContext(), $this->getNewestEvent());
    }
}
