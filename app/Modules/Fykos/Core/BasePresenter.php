<?php

declare(strict_types=1);

namespace App\Modules\Fykos\Core;

use App\Models\OldFykos\BootstrapNavBar;
use App\Models\OldFykos\Jumbotron;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    protected function getNavItems(): array
    {
        return [];
    }

    protected function includeJumbotron(): bool
    {
        return true;
    }

    protected function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->jumbotron = $this->includeJumbotron();
    }

    public function createComponentFullNav(): BootstrapNavBar
    {
        $fullMenu = new BootstrapNavBar(
            $this->getContext(),
            'full',
            'col-xs-12 col-md-12 col-sm-12 navbar-dark bg-light-fykos'
        );
        $fullMenu->addMenuText(self::getPrimaryItems());
        $fullMenu->addMenuText(self::getSecondaryLeftItems());
        $fullMenu->addMenuText(self::getSecondaryRightItems());
        return $fullMenu;
    }

    public function createComponentPrimaryNav(): BootstrapNavBar
    {
        $primaryMenu = new BootstrapNavBar($this->getContext(), 'primary', 'navbar bg-light');
        $primaryMenu->addMenuText(self::getPrimaryItems(), 'mr-auto');
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
        return [];
        //return [new NavItem(new PageTitle(null, _('Upload solutions'), 'fa fa-sign-in'), 'https://db.fykos.cz')];
    }

    private function getSecondaryLeftItems(): array
    {
        return [
//            new NavItem(new PageTitle(null, _('Zadání'), 'fa fa-pencil-square-o'), ':zadani'),
//            new NavItem(new PageTitle(null, _('Pořadí'), 'fa fa-trophy'), ':poradi:start'),
//            new NavItem(new PageTitle(null, _('Fyziklání 2023'), 'fa fa-paper-plane'), 'https://fyziklani.cz/'),
//            new NavItem(new PageTitle(null, _('Fyziklání Online'), 'fa fa-tv'), 'https://online.fyziklani.cz/'),
//            new NavItem(new PageTitle(null, _('DSEF'), 'fa fa-magnet'), 'https://dsef.cz/'),
//            new NavItem(new PageTitle(null, _('Experimenty'), 'fa fa-flask'), ':sex:start'),
        ];
    }

    protected function createComponentJumbotron(): Jumbotron
    {
        return new Jumbotron($this->getContext());
    }

    private function getPrimaryItems(): array
    {
        return [
            new NavItem(new PageTitle(null, 'Zadání', ''), ':zadani'), //fa fa-pencil-square-o
            new NavItem(new PageTitle(null, 'Pořadí', ''), ':poradi:start'), //fa fa-trophy
            new NavItem(
                new PageTitle(
                    null,
                    'O nás',
                    '' //fa fa-group
                ),
                ':about:fykos-group',
                [],
                [
                    new NavItem(new PageTitle(null, 'Co je FYKOS?'), ':about:fykos-group'),
                    new NavItem(new PageTitle(null, 'Organizátoři'), ':o-nas:organizatori'),
                    new NavItem(new PageTitle(null, 'Historie'), 'about:history'),
                    new NavItem(new PageTitle(null, 'Kontakt'), ':o-nas:kontakt'),
                ],
            ),
            new NavItem(
                new PageTitle(
                    null,
                    'Jak řešit',
                    '' //fa fa-book
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
                    '' //fa fa-tasks
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
            new NavItem(new PageTitle(null, 'Akce', ''), ':akce:start'), //fa fa-calendar-check-o
            new NavItem(new PageTitle(null, 'Login', ''), 'https://db.fykos.cz'), //fa fa-sign-in
//            new NavItem(
//                new PageTitle(
//                    null,
//                    'Odkazy',
//                    'fa fa-external-link-square'
//                ),
//                ':odkazy',
//                [],
//                [
//                    new NavItem(new PageTitle(null, 'Doporučené odkazy'), ':odkazy'),
//                    new NavItem(new PageTitle(null, 'Náměty ke čtení'), ':dopoknihy'),
//                ],
//            ),
        ];
    }
}
