<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use App\Modules\Core\Language;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Fof\Core\BasePresenter
{
    /**
     * @throws \Throwable
     */
    protected function getNavItems(): array
    {
        $items = [];

        if ($this->language === Language::cs) {
            $items[] = new NavItem(
                new PageTitle(null, 'O soutěži'),
                ':Default:AboutTheCompetition:default'
            );
            $items[] = new NavItem(
                new PageTitle(null, 'Historie'),
                ':Default:History:default'
            );
        } else {
            $items[] = new NavItem(
                new PageTitle(null, 'About us'),
                ':Default:AboutTheCompetition:default',
                [],
                [
                    new NavItem(new PageTitle(null, 'What Is Fyziklani'), ':Default:AboutTheCompetition:default'),
                    new NavItem(new PageTitle(null, 'History'), ':Default:History:default')
                ]
            );
        }

        $items[] = new NavItem(
            new PageTitle(null, $this->csen('Pravidla', 'Rules')),
            ':Default:Rules:default',
        );

        $items[] = new NavItem(
            new PageTitle(null, $this->csen('Ubytování', 'Accommodation')),
            ':Default:Accommodation:default'
        );

        $items[] = new NavItem(
            new PageTitle(null, $this->csen('Program', 'Schedule')),
            ':Default:Schedule:default',
        );

        if ($this->language === Language::en) {
            $items[] = new NavItem(
                new PageTitle(null, $this->csen('', 'Travel')),
                ':Default:TravelSupport:default',
            );
        }

        if ($this->getPresenterByName('Default:Teams')->isVisible()) {
            $items[] = new NavItem(
                new PageTitle(null, $this->csen('Týmy', 'Teams')),
                ':Default:Teams:',
            );
        }

        if ($this->getPresenterByName('Default:Registration')->isVisible()) {
            $items[] = new NavItem(
                new PageTitle(null, $this->csen('Registrace', 'Registration')),
                ':Default:Registration:',
            );
        }

        return $items;
    }
}
