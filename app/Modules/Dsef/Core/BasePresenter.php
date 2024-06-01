<?php

declare(strict_types=1);

namespace App\Modules\Dsef\Core;

use App\Models\NetteDownloader\ORM\Models\ModelEvent;
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

    public static function getEventKey(ModelEvent $event): string
    {
        return $event->getYear() . '-' . $event->getMonth();
    }

    protected function localize(): void
    {
        $this->lang = 'cs';
        parent::localize();
    }

    /**
     * @return NavItem[]
     */
    protected function getNavItems(): array
    {
        $items = [];
        $items[] = new NavItem(
            new PageTitle('Registrace', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'Registration:',
        );
        $items[] = new NavItem(
            new PageTitle('Aktuální ročník', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'Current:',
        );
        $items[] = new NavItem(
            new PageTitle('Minulé ročníky', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'Archive:',
        );

        return $items;
    }
}
