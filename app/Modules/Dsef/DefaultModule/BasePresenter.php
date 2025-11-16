<?php

declare(strict_types=1);

namespace App\Modules\Dsef\DefaultModule;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Dsef\Core\BasePresenter
{
    /**
     * @return NavItem[]
     * @throws \Throwable
     */
    protected function getNavItems(): array
    {
        $items = [];
        if ($this->getPresenterByName('Default:Registration')->isVisible()) {
            $items[] = new NavItem(
                new PageTitle('Registrace', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
                'Registration:',
            );
        }

        if ($this->getPresenterByName('Default:Current')->isVisible()) {
            $items[] = new NavItem(
                new PageTitle('Aktuální ročník', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
                'Current:',
            );
        }

        $items[] = new NavItem(
            new PageTitle('Minulé ročníky', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'Archive:',
        );
        return $items;
    }
}
