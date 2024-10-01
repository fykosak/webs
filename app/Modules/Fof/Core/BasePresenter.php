<?php

declare(strict_types=1);

namespace App\Modules\Fof\Core;

use App\Models\Downloader\EventModel;
use App\Modules\Core\EventWebPresenter;

abstract class BasePresenter extends EventWebPresenter
{
    public static function createEventKey(EventModel $event): string
    {
        return $event->begin->format('Y');
    }

    protected function getEventIds(): array
    {
        return [1];
    }
}
