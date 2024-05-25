<?php

declare(strict_types=1);

namespace App\Modules\Fol\Core;

use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use App\Models\NetteDownloader\ORM\Services\ServiceEventList;
use App\Modules\Core\EventWebPresenter;
use Nette\Application\UI\Template;

abstract class BasePresenter extends EventWebPresenter
{
    public static function createEventKey(ModelEvent $event): string
    {
        $year = $event->begin->format('Y');
        $month = $event->begin->format('m');
        $monthName = strtolower($event->begin->format('M'));
        return $month < 10 ? ($year . '-' . $monthName) : $year;
    }

    /**
     * @throws \Throwable
     */
    protected function createTemplate(): Template
    {
        $template = parent::createTemplate();
        $template->fofEvent = $this->serviceEventList->getNewest([
                $this->context->getParameters()['fofEventTypeId'],
            ]
        );
        return $template;
    }

    protected function getEventIds(): array
    {
        return [9];
    }
}
