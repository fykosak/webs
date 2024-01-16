<?php

declare(strict_types=1);

namespace App\Modules\Core;

use App\Models\GamePhaseCalculator;
use App\Models\NetteDownloader\ORM\Services\DummyService;
use Nette\Application\UI\Template;

abstract class EventWebPresenter extends BasePresenter
{
    protected GamePhaseCalculator $gamePhaseCalculator;
    protected DummyService $dummyService;

    public function injectEventWebServices(
        GamePhaseCalculator $calculator,
        DummyService $dummyService
    ): void {
        $this->gamePhaseCalculator = $calculator;
        $this->dummyService = $dummyService;
    }

    protected function createTemplate(): Template
    {
        $template = parent::createTemplate();
        $template->gamePhaseCalculator = $this->gamePhaseCalculator;
        return $template;
    }
}
