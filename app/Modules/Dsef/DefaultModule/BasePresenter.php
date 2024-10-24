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
        if (RegistrationPresenter::isVisible($this->getNewestEvent())) {
            $items[] = new NavItem(
                new PageTitle('Registrace', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
                'Registration:',
            );
        }

        if (CurrentPresenter::isVisible($this->getNewestEvent())) {
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
