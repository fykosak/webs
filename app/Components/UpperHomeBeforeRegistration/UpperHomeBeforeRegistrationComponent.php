<?php

declare(strict_types=1);

namespace App\Components\UpperHomeBeforeRegistration;

use App\Components\Countdown\CountdownComponent;
use App\Models\GamePhaseCalculator;
use Nette\Application\UI\Control;

class UpperHomeBeforeRegistrationComponent extends Control
{
    protected GamePhaseCalculator $gamePhaseCalculator;

    public function __construct(GamePhaseCalculator $calculator)
    {
        $this->gamePhaseCalculator = $calculator;
    }

    public function render(): void
    {
        $this->template->gamePhaseCalculator = $this->gamePhaseCalculator;
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomeBeforeRegistration.latte');
    }

    protected function createComponentCountdown(): CountdownComponent
    {
        return new CountdownComponent($this->gamePhaseCalculator->getFKSDBEvent()->registrationBegin);
    }
}
