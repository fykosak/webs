<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\Core;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Core\ContestPresenter
{
    /**
     * @return NavItem[]
     */
    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new PageTitle(null, 'Zadání', 'fa-solid fa-file-pen'),
            ':Default:Problems:default'
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Pořadí', 'fa-solid fa-ranking-star'),
            ':Default:Results:default'
        );

        $items[] = new NavItem(
            new PageTitle(null, 'O nás', 'fa-solid fa-users'),
            ':Default:About:default',
            [],
            [
                new NavItem(new PageTitle(null, 'Co je Výfuk?'), ':Default:About:default'),
                new NavItem(new PageTitle(null, 'Organizátoři'), ':Default:About:organizers'),
                new NavItem(new PageTitle(null, 'Historie'), ':Default:About:history'),
                new NavItem(new PageTitle(null, 'Síň slávy'), ':Default:About:pastOrganizers'),
                new NavItem(new PageTitle(null, 'Podpořte nás'), ':Default:About:sponsors'),
                new NavItem(new PageTitle(null, 'Kontakt'), ':Default:About:contact'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Jak řešit', 'fa-solid fa-book'),
            ':Default:HowToSolve:default',
            [],
            [
                new NavItem(new PageTitle(null, 'Jak se zapojit'), ':Default:HowToSolve:default'),
                new NavItem(new PageTitle(null, 'Pravidla'), ':Default:HowToSolve:rules'),
                new NavItem(new PageTitle(null, 'Jak psát řešení'), ':Default:HowToSolve:solutions'),
                new NavItem(new PageTitle(null, 'Jak psát experimenty'), ':Default:HowToSolve:experiments'),
                new NavItem(new PageTitle(null, 'Výfučí bingo'), ':Default:Bingo:'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Akce', 'fa-solid fa-calendar-days'),
            ':Default:Events:'
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Pro učitele', 'fa-solid fa-user-graduate'),
            ':Default:Teachers:default',
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Přihlásit se', "icon icon-fksdb"),
            'https://db.fykos.cz'
        );

        return $items;
    }

    protected function localize(): void
    {
        $this->lang = 'cs';
        parent::localize();
    }
    public function getContestId(): int
    {
        return 2;
    }
}
