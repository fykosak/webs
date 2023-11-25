<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Modules\Dsef\DefaultModule\CurrentPresenter;
use App\Modules\Dsef\DefaultModule\RegistrationPresenter;
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
            [],
            [
                new NavItem(new PageTitle(null, 'Co je FYKOS?'), 'About:default'),
                new NavItem(new PageTitle(null, 'Organizátoři'), 'About:organizers'),
                new NavItem(new PageTitle(null, 'Historie'), 'About:history'),
                new NavItem(new PageTitle(null, 'Kontakt'), 'About:contact')
            ],
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Akce', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Events:Default:',
        );

        $items[] = new NavItem(
            new PageTitle(null, "Seminář", 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Events:Fykos:default',
            [],
            [
                new NavItem(new PageTitle(null, 'Základní informace'), ':Events:Fykos:default'),
                new NavItem(new PageTitle(null, 'Pravidla'), ':Events:Fykos:rules'),
                new NavItem(new PageTitle(null, 'Jak na experimenty'), ':Events:Fykos:experiments'),
                new NavItem(new PageTitle(null, 'Jak psát řešení'), ':Events:Fykos:textutorial')
            ],
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
            ':Default:ProblemsArchive:',
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Přihlásit se', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'https://db.fykos.cz',
        );
        return $items;
    }
}
