<?php

declare(strict_types=1);

namespace App\Modules\Core;

use App\Models\GamePhaseCalculator;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Nette\Application\UI\Template;

abstract class EventWebPresenter extends BasePresenter
{
    protected ServiceEventDetail $serviceEventDetail;
    protected GamePhaseCalculator $gamePhaseCalculator;

    public function injectEventWebServices(
        ServiceEventDetail $serviceEventDetail,
        GamePhaseCalculator $calculator
    ): void {
        $this->serviceEventDetail = $serviceEventDetail;
        $this->gamePhaseCalculator = $calculator;
    }

    protected function createTemplate(): Template
    {
        $template = parent::createTemplate();
        $template->gamePhaseCalculator = $this->gamePhaseCalculator;
        return $template;
    }
}
