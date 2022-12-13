<?php

declare(strict_types=1);

namespace App\Modules\Fykos\Core;

use App\Components\Navigation\Navigation;
use App\Models\OldFykos\BootstrapNavBar;
use App\Models\OldFykos\NavBarItem;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

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
        return [new NavItem(new PageTitle(null, 'Přihlásit se', 'fa fa-sign-in'), 'https://db.fykos.cz')];
    }

    private function getSecondaryLeftItems(): array
    {
        return [
            new NavItem(new PageTitle(null, 'Zadání', 'fa fa-pencil-square-o'), ':zadani'),
            new NavItem(new PageTitle(null, 'Pořadí', 'fa fa-trophy'), ':poradi:start'),
            new NavItem(new PageTitle(null, 'Fyziklání 2023', 'fa fa-paper-plane'), 'https://fyziklani.cz/'),
            new NavItem(new PageTitle(null, 'Fyziklání Online', 'fa fa-tv'), 'https://online.fyziklani.cz/'),
            new NavItem(new PageTitle(null, 'DSEF', 'fa fa-magnet'), 'https://dsef.cz/'),
            new NavItem(new PageTitle(null, 'Experimenty', 'fa fa-flask'), ':sex:start'),
        ];
    }

    private function getPrimaryItems(): array
    {
        return [
            new NavItem(
                new PageTitle(
                    null,
                    'O FYKOSu',
                    'fa fa-group'
                ),
                ':o-nas:co-je-fykos',
                [],
                [
                    new NavItem(new PageTitle(null, 'Co je FYKOS?'), ':o-nas:co-je-fykos'),
                    new NavItem(new PageTitle(null, 'Organizátoři'), ':o-nas:organizatori'),
                    new NavItem(new PageTitle(null, 'Historie'), ':o-nas:historie'),
                    new NavItem(new PageTitle(null, 'Kontakt'), ':o-nas:kontakt'),
                ],
            ),
            new NavItem(
                new PageTitle(
                    null,
                    'Jak řešit',
                    'fa fa-book'
                ),
                '#',
                [],
                [
                    new NavItem(new PageTitle(null, 'Pravidla'), ':o-nas:pravidla'),
                    new NavItem(new PageTitle(null, 'Elektronická řešení'), ':ulohy:elektronicka-reseni'),
                    new NavItem(new PageTitle(null, 'Termíny'), ':terminy'),
                ],
            ),
            new NavItem(
                new PageTitle(
                    null,
                    'Úlohy',
                    'fa fa-tasks'
                ),
                ':ulohy:start',
                [],
                [
                    new NavItem(new PageTitle(null, 'Podle oboru'), ':ulohy:start'),
                    new NavItem(new PageTitle(null, 'Podle ročníků'), ':ulohy:archiv'),
                    new NavItem(new PageTitle(null, 'Všechny seriálové úlohy'), ':ulohy:serial'),
                    new NavItem(new PageTitle(null, 'Ročenky'), ':ulohy:rocenky'),
                    new NavItem(new PageTitle(null, 'Experimenty'), ':sex:start'),
                ]
            ),
            new NavItem(new PageTitle(null, 'Akce', 'fa fa-calendar-check-o'), ':akce:start'),
            new NavItem(
                new PageTitle(
                    null,
                    'Odkazy',
                    'fa fa-external-link-square'
                ),
                ':odkazy',
                [],
                [
                    new NavItem(new PageTitle(null, 'Doporučené odkazy'), ':odkazy'),
                    new NavItem(new PageTitle(null, 'Náměty ke čtení'), ':dopoknihy'),
                    new NavItem(new PageTitle(null, 'Fyzikální olympiáda'), 'https://fyzikalniolympiada.cz/'),
                ],
            ),
        ];
    }
}
