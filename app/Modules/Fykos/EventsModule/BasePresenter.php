<?php

declare(strict_types=1);

namespace App\Modules\Fykos\EventsModule;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Fykos\Core\BasePresenter
{
    /**
     * @throws \Throwable
     */
    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new PageTitle(null, "O nás", 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'About:',
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Akce', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Events:Default:',
        );

        $items[] = new NavItem(
            new PageTitle(null, "Seminář", 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Events:Fykos:default',
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Zadání', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'Problems:',
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Pořadí', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'Results:',
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Archiv úloh', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'Default',
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Přihlásit se', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'https://db.fykos.cz',
        );
        return $items;
    }
}
