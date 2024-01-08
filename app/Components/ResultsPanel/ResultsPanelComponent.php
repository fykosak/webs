<?php

declare(strict_types=1);

namespace App\Components\ResultsPanel;

use App\Components\ApiResults\ApiResultsComponent;
use App\Models\GamePhaseCalculator;
use Fykosak\Utils\BaseComponent\BaseComponent;

class ResultsPanelComponent extends BaseComponent
{
    private GamePhaseCalculator $gamePhaseCalculator;

    public function injectGamePhaseCalculator(GamePhaseCalculator $calculator): void
    {
        $this->gamePhaseCalculator = $calculator;
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentApiResults(): ApiResultsComponent
    {
        return new ApiResultsComponent($this->getContext(), $this->gamePhaseCalculator->getFKSDBEvent()->eventId);
    }

    public function render(bool $dark = false): void
    {
        $this->template->dark = $dark;
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->gamePhaseCalculator = $this->gamePhaseCalculator;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'panel.latte');
    }
}
