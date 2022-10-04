<?php

declare(strict_types=1);

namespace App\Modules\Dsef\DefaultModule;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Dsef\Core\BasePresenter
{

    /**
     * @throws \Throwable
     */
    protected function getNavItems(): array
    {
        $items = [];
        $items[] = new NavItem(
            new PageTitle(null, "Registrace", 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'Registration:',
        );
        $items[] = new NavItem(
            new PageTitle(null, "Aktuální ročník", 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'Current:',
        );
        $items[] = new NavItem(
            new PageTitle(null, "Minulé ročníky", 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'Archive:',
        );


        return $items;
    }
}
