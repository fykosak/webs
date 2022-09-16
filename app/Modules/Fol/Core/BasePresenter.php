<?php

declare(strict_types=1);

namespace App\Modules\Fol\Core;

use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;

abstract class BasePresenter extends \App\Modules\Core\EventWebPresenter
{
    public static function createEventKey(ModelEvent $event): string
    {
        $year = $event->begin->format('Y');
        $month = $event->begin->format('m');
        $monthName = strtolower($event->begin->format('M'));
        return $month < 10 ? ($year . '-' . $monthName) : $year;
    }
}
