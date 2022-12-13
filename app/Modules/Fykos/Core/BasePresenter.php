<?php

declare(strict_types=1);

namespace App\Modules\Fykos\Core;

use App\Models\OldFykos\BootstrapNavBar;
use App\Models\OldFykos\NavBarItem;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    protected function getNavItems(): array
    {
        return [];
    }

    public function createComponentFullNav(): BootstrapNavBar
    {
        $fullMenu = new BootstrapNavBar(
            $this->getContext(),
            'full',
            'col-xs-12 col-md-12 col-sm-12  navbar-dark bg-light-fykos'
        );
        $fullMenu->addMenuText(self::getPrimaryItems());
        $fullMenu->addMenuText(self::getSecondaryLeftItems());
        $fullMenu->addMenuText(self::getSecondaryRightItems());
        $fullMenu->addLangSelect('justify-content-end');
        return $fullMenu;
    }

    public function createComponentPrimaryNav(): BootstrapNavBar
    {
        $primaryMenu = new BootstrapNavBar($this->getContext(), 'primary', 'navbar bg-light');
        $primaryMenu->addMenuText(self::getPrimaryItems(), 'mr-auto');
        $primaryMenu->addLangSelect();
        return $primaryMenu;
    }

    public function createComponentSecondaryNav(): BootstrapNavBar
    {
        $secondMenu = new BootstrapNavBar($this->getContext(), 'secondary', ' navbar-dark bg-light-fykos container');
        $secondMenu->addMenuText(self::getSecondaryLeftItems(), 'mr-auto');
        $secondMenu->addMenuText(self::getSecondaryRightItems());
        return $secondMenu;
    }

    private function getSecondaryRightItems(): array
    {
        return [new NavBarItem('https://db.fykos.cz', ' Přihlásit se', 'fa fa-sign-in')];
    }

    private function getSecondaryLeftItems(): array
    {
        return [
            new NavBarItem(':zadani', 'Zadání', 'fa fa-pencil-square-o'),
            new NavBarItem(':poradi:start', 'Pořadí', 'fa fa-trophy'),
            new NavBarItem('https://fyziklani.cz/', 'Fyziklání 2023', 'fa fa-paper-plane'),
            new NavBarItem('https://online.fyziklani.cz/', 'Fyziklání Online', 'fa fa-tv'),
            new NavBarItem('https://dsef.cz/', 'DSEF', 'fa fa-magnet'),
            new NavBarItem(':sex:start', 'Experimenty', 'fa fa-flask'),
        ];
    }

    private function getPrimaryItems(): array
    {
        return [
            new NavBarItem(
                ':o-nas:co-je-fykos',
                'O FYKOSu',
                'fa fa-group',
                [
                    new NavBarItem(':o-nas:co-je-fykos', 'Co je FYKOS?'),
                    new NavBarItem(':o-nas:organizatori', 'Organizátoři'),
                    new NavBarItem(':o-nas:historie', 'Historie'),
                    new NavBarItem(':o-nas:kontakt', 'Kontakt'),
                ],
            ),
            new NavBarItem(
                '#',
                'Jak řešit',
                'fa fa-book',
                [
                    new NavBarItem(':o-nas:pravidla', 'Pravidla'),
                    new NavBarItem(':ulohy:elektronicka-reseni', 'Elektronická řešení'),
                    new NavBarItem(':terminy', 'Termíny'),
                ],
            ),
            new NavBarItem(
                ':ulohy:start',
                'Úlohy',
                'fa fa-tasks',
                [
                    new NavBarItem(':ulohy:start', 'Podle oboru'),
                    new NavBarItem(':ulohy:archiv', 'Podle ročníků'),
                    new NavBarItem(':ulohy:serial', 'Všechny seriálové úlohy'),
                    new NavBarItem(':ulohy:rocenky', 'Ročenky'),
                    new NavBarItem(':sex:start', 'Experimenty'),
                ]
            ),
            new NavBarItem(':akce:start', 'Akce', 'fa fa-calendar-check-o '),
            new NavBarItem(
                ':odkazy',
                'Odkazy',
                'fa fa-external-link-square',
                [
                    new NavBarItem(':odkazy', 'Doporučené odkazy'),
                    new NavBarItem(':dopoknihy', 'Náměty ke čtení'),
                    new NavBarItem('http://fyzikalniolympiada.cz/', 'Fyzikální olympiáda'),
                ],
            ),
        ];
    }
}
