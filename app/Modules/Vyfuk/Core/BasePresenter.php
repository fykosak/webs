<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\Core;

use App\Modules\Core\ContestPresenter;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\Title;

abstract class BasePresenter extends ContestPresenter
{
    /**
     * @return NavItem[]
     */
    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new Title(null, 'Zadání', 'fa-solid fa-file-pen'),
            ':Default:Problems:default'
        );

        $items[] = new NavItem(
            new Title(null, 'Pořadí', 'fa-solid fa-ranking-star'),
            ':Default:Results:default'
        );

        $items[] = new NavItem(
            new Title(null, 'O nás', 'fa-solid fa-users'),
            ':Default:About:default',
            [],
            [
                new NavItem(new Title(null, 'Co je Výfuk?'), ':Default:About:default'),
                new NavItem(new Title(null, 'Organizátoři'), ':Default:About:organizers'),
                new NavItem(new Title(null, 'Historie'), ':Default:About:history'),
                new NavItem(new Title(null, 'Síň slávy'), ':Default:About:pastOrganizers'),
                new NavItem(new Title(null, 'Podpořte nás'), ':Default:About:sponsors'),
                new NavItem(new Title(null, 'Kontakt'), ':Default:About:contact'),
            ],
        );

        $items[] = new NavItem(
            new Title(null, 'Jak řešit', 'fa-solid fa-book'),
            ':Default:HowToSolve:default',
            [],
            [
                new NavItem(new Title(null, 'Jak se zapojit'), ':Default:HowToSolve:default'),
                new NavItem(new Title(null, 'Pravidla'), ':Default:HowToSolve:rules'),
                new NavItem(new Title(null, 'Jak psát řešení'), ':Default:HowToSolve:solutions'),
                new NavItem(new Title(null, 'Jak psát experimenty'), ':Default:HowToSolve:experiments'),
                new NavItem(new Title(null, 'Výfučí bingo'), ':Default:Bingo:'),
            ],
        );

        $items[] = new NavItem(
            new Title(null, 'Akce', 'fa-solid fa-calendar-days'),
            ':Default:Events:'
        );

        $items[] = new NavItem(
            new Title(null, 'Pro učitele', 'fa-solid fa-user-graduate'),
            ':Default:Teachers:default',
        );

        $items[] = new NavItem(
            new Title(null, 'Přihlásit se', 'icon icon-fksdb'),
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
