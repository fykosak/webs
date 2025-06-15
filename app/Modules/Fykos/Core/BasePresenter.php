<?php

declare(strict_types=1);

namespace App\Modules\Fykos\Core;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;
use App\Components\OrgSneakPeak\OrgSneakPeakComponent;

abstract class BasePresenter extends \App\Modules\Core\ContestPresenter
{
    /**
     * @return NavItem[]
     */
    protected function getNavItems(): array
    {
        $icon = 'visible-sm-inline glyphicon glyphicon-info-sign';

        $items = [];

        $items[] = new NavItem(
            new PageTitle($this->csen('O nás', 'About Us'), $icon), // TODO
            ':Default:About:',
            [],
            [
                new NavItem(new PageTitle($this->csen('Co je FYKOS?', 'What Is FYKOS?')), ':Default:About:default'),
                new NavItem(new PageTitle($this->csen('Organizátoři', 'Organizers')), ':Default:About:organizers'),
                new NavItem(new PageTitle($this->csen('Historie', 'History')), ':Default:About:history'),
                new NavItem(new PageTitle($this->csen('Kontakt', 'Contact')), ':Default:About:contact'),
                new NavItem(new PageTitle($this->csen('Podpořte nás', 'Support Us')), ':Default:SupportUs:'),
                new NavItem(new PageTitle($this->csen('Merch', 'Merch')), ':Default:Merch:'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle($this->csen('Akce', 'Events'), $icon), // TODO
            ':Events:Default:',
        );

        $items[] = new NavItem(
            new PageTitle($this->csen('Seminář', 'FYKOS Competition'), $icon),
            ':Events:Fykos:',
            [],
            [
                new NavItem(new PageTitle($this->csen('Základní informace', 'Basic Information')), ':Events:Fykos:'),
                new NavItem(new PageTitle($this->csen('Pravidla', 'Rules')), ':Events:Fykos:rules'),
                new NavItem(
                    new PageTitle($this->csen('Jak psát řešení', 'How to Write Solutions')),
                    ':Events:Fykos:texTutorial'
                ),
                new NavItem(
                    new PageTitle($this->csen('Jak na experimenty', 'How to Do Experiments')),
                    ':Events:Fykos:experiments'
                ),
            ],
        );

        $items[] = new NavItem(
            new PageTitle($this->csen('Zadání', 'Problems'), $icon),
            ':Default:Problems:default',
            // @phpstan-ignore-next-line
            [
                'year' => null,
                'series' => null
            ],
            [
            ]
        );

        $items[] = new NavItem(
            new PageTitle($this->csen('Archiv', 'Archive'), $icon),
            ':Default:Archive:default',
            [],
            [
                new NavItem(
                    new PageTitle($this->csen('Archiv seriálů', 'Serial Archive')),
                    ':Default:Archive:serial',
                ),
            ],
        );

        $items[] = new NavItem(
            new PageTitle($this->csen('Pořadí', 'Results'), $icon),
            ':Default:Results:default',
            // @phpstan-ignore-next-line
            [
                'year' => null
            ]
        );

        $items[] = new NavItem(
            new PageTitle($this->csen('Přihlásit se', 'Sign In'), $icon),
            'https://db.fykos.cz',
        );
        return $items;
    }

    public function getContestId(): int
    {
        return 1;
    }

    public function createComponentOrgSneakPeak(): OrgSneakPeakComponent
    {
        return new OrgSneakPeakComponent($this->getContext());
    }
}
