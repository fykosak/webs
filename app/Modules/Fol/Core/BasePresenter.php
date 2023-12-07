<?php

declare(strict_types=1);

namespace App\Modules\Fol\Core;

use App\Models\GamePhaseCalculator;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventList;

abstract class BasePresenter extends \App\Modules\Core\EventWebPresenter
{
    public static function createEventKey(ModelEvent $event): string
    {
        $year = $event->begin->format('Y');
        $month = $event->begin->format('m');
        $monthName = strtolower($event->begin->format('M'));
        return $month < 10 ? ($year . '-' . $monthName) : $year;
    }

    private ServiceEventList $serviceEventList;

    public function injectServiceEventList(ServiceEventList $serviceEventList)
    {
        $this->serviceEventList = $serviceEventList;
    }

    protected function createTemplate(): \Nette\Application\UI\Template
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
