<?php

declare(strict_types=1);

namespace App\Modules\Fol\Core;

use App\Models\GamePhaseCalculator;
use App\Modules\Core\EventWebPresenter;
use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use App\Models\NetteDownloader\ORM\Services\ServiceEventList;
use Nette\Application\UI\Template;

abstract class BasePresenter extends EventWebPresenter
{
    private ServiceEventList $serviceEventList;

    public static function createEventKey(ModelEvent $event): string
    {
        $year = $event->begin->format('Y');
        $month = $event->begin->format('m');
        $monthName = strtolower($event->begin->format('M'));
        return $month < 10 ? ($year . '-' . $monthName) : $year;
    }

    public function injectServiceEventList(ServiceEventList $serviceEventList): void
    {
        $this->serviceEventList = $serviceEventList;
    }

    /**
     * @throws \Throwable
     */
    protected function createTemplate(): Template
    {
        $fofGamePhaseCalculator = new GamePhaseCalculator(
            $this->context->getParameters()['fofEventTypeId'],
            $this->serviceEventList,
            $this->context
        );

        $template = parent::createTemplate();
        $template->fofEvent = $fofGamePhaseCalculator->getFKSDBEvent();
        return $template;
    }
}
