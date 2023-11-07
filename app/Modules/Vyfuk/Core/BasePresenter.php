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
            new NavItem(new PageTitle(null, 'O Výfuku'), ':default'),
            new NavItem(new PageTitle(null, 'Zadání', 'fas fa-pen'), ':default'),
            new NavItem(new PageTitle(null, 'Pořadí'), ':default'),
            new NavItem(new PageTitle(null, 'Akce'), ':default', [], [
                new NavItem(new PageTitle(null, 'Tábor'), ':default'),
                new NavItem(new PageTitle(null, 'Setkání'), ':default'),
                new NavItem(new PageTitle(null, 'Ostatní'), ':default')
            ]),
            new NavItem(new PageTitle(null, 'Login'), 'https://db.fykos.cz')
        ];
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
