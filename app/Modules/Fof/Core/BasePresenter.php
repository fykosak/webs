<?php

declare(strict_types=1);

namespace App\Modules\Fof\Core;

use App\Modules\Core\EventWebPresenter;
use App\Models\NetteDownloader\ORM\Models\ModelEvent;

abstract class BasePresenter extends EventWebPresenter
{
    public static function createEventKey(ModelEvent $event): string
    {
        return $event->begin->format('Y');
    }
}
