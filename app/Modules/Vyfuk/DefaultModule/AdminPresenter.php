<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

class AdminPresenter extends BasePresenter
{
    /**
     * @return NavItem[]
     */
    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new PageTitle('Správa novinek', 'fa-solid fa-newspaper'),
            ':Default:Admin:news'
        );

        $items[] = new NavItem(
            new PageTitle('Správa souborů', 'fa-solid fa-file-pen'),
            ':Default:Admin:files'
        );

        $items[] = new NavItem(
            new PageTitle('Správa fotek', 'fa-solid fa-images'),
            ':Default:Admin:media'
        );

        $items[] = new NavItem(
            new PageTitle('Adin dashboard', 'fa-solid fa-user-gear'),
            ':Default:Admin:default'
        );

        return $items;
    }

}
