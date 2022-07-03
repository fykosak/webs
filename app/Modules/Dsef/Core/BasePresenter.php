<?php

declare(strict_types=1);

namespace App\Modules\Dsef\Core;

use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    public static $months = [
        "leden", "unor", "brezen", "duben", "kveten", "cerven",
        "cervenec", "srpen", "zari", "rijen", "listopad", "prosinec"];

    public static function getEventYear(ModelEvent $event): string
    {
        return $event->begin->format('Y');
    }

    public static function getEventMonth(ModelEvent $event): string
    {
        return self::$months[(int)$event->begin->format('n') - 1];
    }

    public static function getEventKey(ModelEvent $event): string
    {
        return self::getEventYear($event) . '-' . self::getEventMonth($event);
    }
}
