<?php

declare(strict_types=1);

namespace App\Modules\Fof\Core;

use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use App\Modules\Core\EventWebPresenter;

abstract class BasePresenter extends EventWebPresenter
{
    public const EVENT_IDS = [9];

    public static function createEventKey(ModelEvent $event): string
    {
        return $event->begin->format('Y');
    }
}
