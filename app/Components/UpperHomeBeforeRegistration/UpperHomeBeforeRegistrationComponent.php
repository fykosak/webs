<?php

declare(strict_types=1);

namespace App\Components\UpperHomeBeforeRegistration;

use App\Components\Countdown\CountdownComponent;
use App\Models\GamePhaseCalculator;
use Fykosak\Utils\Components\DIComponent;

class UpperHomeBeforeRegistrationComponent extends DIComponent
{
    protected GamePhaseCalculator $gamePhaseCalculator;

    public function inject(GamePhaseCalculator $calculator): void
    {
        $this->gamePhaseCalculator = $calculator;
    }

    public function render(): void
    {
        $this->template->gamePhaseCalculator = $this->gamePhaseCalculator;
        $this->template->lang = $this->translator->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomeBeforeRegistration.latte');
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentCountdown(): CountdownComponent
    {
        return new CountdownComponent(
            $this->getContext(),
            $this->gamePhaseCalculator->getFKSDBEvent()->registrationBegin
        );
    }
}
