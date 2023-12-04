<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\Core;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new PageTitle(null, 'O Nás'),
            ':default',
            [],
            [
                new NavItem(new PageTitle(null, 'Co je Výfuk?'), ':Default:About:default'),
                new NavItem(new PageTitle(null, 'Historie'), ':Default:About:History'),
                new NavItem(new PageTitle(null, 'Organizátoři'), ':Default:About:Organizers'),
                new NavItem(new PageTitle(null, 'Kontakt'), ':Default:About:Contact'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Zadání', 'fas fa-pen'),
            ':Default:Problems:default'
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Pořadí'),
            ':Default:Results:default'
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Akce'),
            ':default',
            [],
            [
                new NavItem(new PageTitle(null, 'Tábor'), ':default'), //TODO
                new NavItem(new PageTitle(null, 'Setkání'), ':default'), //TODO
            ],
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Archiv úloh'),
            ':Default:Archive:default'
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Přihlásit se'),
            'https://db.fykos.cz'
        );

        return $items;
    }

    protected function localize(): void
    {
        $this->lang = 'cs';
        parent::localize();
    }

    protected function beforeRender(): void
    {
        parent::beforeRender();
    }
}
