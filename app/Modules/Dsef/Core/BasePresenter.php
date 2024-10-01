<?php

declare(strict_types=1);

namespace App\Modules\Dsef\Core;

use App\Models\Downloader\EventModel;
use App\Modules\Core\EventWebPresenter;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends EventWebPresenter
{
    public const EVENT_IDS = [2, 14];

    protected function getEventIds(): array
    {
        return [2, 14];
    }

    public static function getEventKey(EventModel $event): string
    {
        return $event->getYear() . '-' . $event->getMonth();
    }

    protected function localize(): void
    {
        $this->lang = 'cs';
        parent::localize();
    }

}
