<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\Core;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    protected function getNavItems(): array
    {
        return [
            new NavItem(new PageTitle(null, 'O Výfuku'), ':Default:Default:default'),
            new NavItem(new PageTitle(null, 'Zadání', 'fas fa-pen'), ':Default:Default:default'),
            new NavItem(new PageTitle(null, 'Pořadí'), ':Default:Default:default'),
            new NavItem(new PageTitle(null, 'Akce'), ':Default:Default:default', [], [
                new NavItem(new PageTitle(null, 'Tábor'), ':Default:Default:default'),
                new NavItem(new PageTitle(null, 'Setkání'), ':Default:Default:default'),
                new NavItem(new PageTitle(null, 'Ostatní'), ':Default:Default:default')
            ]),
            new NavItem(new PageTitle(null, 'Login'), 'https://db.fykos.cz')
        ];
    }

    protected function localize(): void
    {
        $this->lang = 'cs';
        parent::localize();
    }
}
