<?php

declare(strict_types=1);

namespace App\Components\UpperHomePrague;

use App\Components\Countdown\CountdownComponent;
use App\Models\GamePhaseCalculator;
use Fykosak\Utils\Components\DIComponent;

final class UpperHomePrague extends DIComponent
{
    private GamePhaseCalculator $gamePhaseCalculator;

    public function injectGamePhaseCalculator(GamePhaseCalculator $gamePhaseCalculator): void
    {
        $this->gamePhaseCalculator = $gamePhaseCalculator;
    }

    /**
     * @throws \Throwable
     */
    public function render(): void
    {
        $this->template->lang = $this->translator->lang;
        $this->template->gamePhaseCalculator = $this->gamePhaseCalculator;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomePrague.latte');
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentCountdown(): CountdownComponent
    {
        if ($this->gamePhaseCalculator->isRegistration($this->gamePhaseCalculator::BEFORE)) {
            return new CountdownComponent(
                $this->container,
                $this->gamePhaseCalculator->getFKSDBEvent()->registrationBegin
            );
        }
        return new CountdownComponent($this->container, $this->gamePhaseCalculator->getGameBegin());
    }
}
