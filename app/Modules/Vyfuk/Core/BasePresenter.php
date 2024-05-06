<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\Core;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    /**
     * @return NavItem[]
     */
    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new PageTitle('O Nás'),
            ':default',
            [],
            [
                new NavItem(new PageTitle('Co je Výfuk?'), ':Default:About:default'),
                new NavItem(new PageTitle('Historie'), ':Default:About:History'),
                new NavItem(new PageTitle('Organizátoři'), ':Default:About:Organizers'),
                new NavItem(new PageTitle('Podpořte nás'), ':Default:About:Sponsors'),
                new NavItem(new PageTitle('Kontakt'), ':Default:About:Contact'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle('TODO název'), //TODO název sekce
            ':default',
            [],
            [
                new NavItem(new PageTitle('Pravidla'), ':Default:Section:Rules'),
                new NavItem(new PageTitle('Jak se zapojit'), ':Default:Section:howtoengage'), //TODO
                new NavItem(new PageTitle('Pro učitele'), ':Default:Section:Teachers'),
                new NavItem(new PageTitle('Jak psát řešení'), ':default'), //TODO
                new NavItem(new PageTitle('Rady a tipy'), ':default'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle('Zadání', 'bi bi-pencil-fill'),
            ':Default:Problems:default'
        );

        $items[] = new NavItem(
            new PageTitle('Pořadí'),
            ':Default:Results:default'
        );

        $items[] = new NavItem(
            new PageTitle('Akce'),
            ':default',
            [],
            [
                new NavItem(new PageTitle('Akce'), ':Default:Events:default'), //TODO uspořádání odkazů
                new NavItem(new PageTitle('Tábor'), ':Default:Events:camp'),
                new NavItem(new PageTitle('Setkání'), ':Default:Events:meeting'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle('Archiv'),
            ':default',
            [],
            [
                new NavItem(new PageTitle('Úlohy'), ':Default:Archive:default'),
                new NavItem(new PageTitle('Výfučtení'), ':Default:Archive:serials'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle('Přihlásit se'),
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
