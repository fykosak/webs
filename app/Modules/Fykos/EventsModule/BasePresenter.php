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
            ':Default:About:',
            [],
            [
                new NavItem(new PageTitle(null, 'Co je FYKOS?'), ':Default:About:default'),
                new NavItem(new PageTitle(null, 'Organizátoři'), ':Default:About:organizers'),
                new NavItem(new PageTitle(null, 'Historie'), ':Default:About:history'),
                new NavItem(new PageTitle(null, 'Kontakt'), ':Default:About:contact')
            ],
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Akce', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'Default:',
        );

        $items[] = new NavItem(
            new PageTitle(null, "Seminář", 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'Fykos:default',
            [],
            [
                new NavItem(new PageTitle(null, 'Základní informace'), 'Fykos:default'),
                new NavItem(new PageTitle(null, 'Pravidla'), 'Fykos:rules'),
                new NavItem(new PageTitle(null, 'Jak na experimenty'), 'Fykos:experiments'),
                new NavItem(new PageTitle(null, 'Jak psát řešení'), 'Fykos:textutorial')
            ],
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Zadání', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:Problems:',
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Pořadí', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:Results:',
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Archiv úloh', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:ProblemsArchive:',
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Přihlásit se', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'https://db.fykos.cz',
        );
        return $items;
    }
}
