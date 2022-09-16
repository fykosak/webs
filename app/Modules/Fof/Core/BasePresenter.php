<?php

declare(strict_types=1);

namespace App\Modules\Fof\Core;

use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;

abstract class BasePresenter extends \App\Modules\Core\EventWebPresenter
{
    public static function createEventKey(ModelEvent $event): string
    {
        return $event->begin->format('Y');
    }
}
