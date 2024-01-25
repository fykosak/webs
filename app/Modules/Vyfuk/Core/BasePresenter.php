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
                new NavItem(new PageTitle(null, 'Podpořte nás'), ':Default:About:Sponsors'),
                new NavItem(new PageTitle(null, 'Kontakt'), ':Default:About:Contact'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle(null, 'TODO název'), //TODO název sekce
            ':default',
            [],
            [
                new NavItem(new PageTitle(null, 'Pravidla'), ':Default:Section:Rules'),
                new NavItem(new PageTitle(null, 'Jak se zapojit'), ':default'), //TODO
                new NavItem(new PageTitle(null, 'Pro učitele'), ':Default:Section:Teachers'),
                new NavItem(new PageTitle(null, 'Jak psát řešení'), ':default'), //TODO
                new NavItem(new PageTitle(null, 'Rady a tipy'), ':default'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Zadání', 'bi bi-pencil-fill'),
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
                new NavItem(new PageTitle(null, 'Akce'), ':Default:Events:default'), //TODO uspořádání odkazů
                new NavItem(new PageTitle(null, 'Tábor'), ':Default:Events:camp'),
                new NavItem(new PageTitle(null, 'Setkání'), ':Default:Events:meeting'),
                new NavItem(new PageTitle(null, 'Kalendář'), ':Default:Events:calendar'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Archiv'),
            ':default',
            [],
            [
                new NavItem(new PageTitle(null, 'Úlohy'), ':Default:Archive:default'),
                new NavItem(new PageTitle(null, 'Výfučtení'), ':Default:Archive:serials'),
            ],
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
}
