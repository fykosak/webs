<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

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

        $items[] = new NavItem(
            new PageTitle($this->csen('O soutěži', 'About'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            ':Default:AboutTheCompetition:default',
        );
        $items[] = new NavItem(
            new PageTitle($this->csen('Historie', 'History'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            ':Default:History:default',
        );
        $items[] = new NavItem(
            new PageTitle(
                $this->csen('Pravidla', 'Rules'),
                'visible-sm-inline glyphicon glyphicon-exclamation-sign'
            ),
            ':Default:Rules:default',
        );
        $items[] = new NavItem(
            new PageTitle(
                $this->csen('Ubytování', 'Accommodation'),
                'visible-sm-inline glyphicon glyphicon-question-sign'
            ),
            ':Default:Accommodation:default',
        );
        $items[] = new NavItem(
            new PageTitle($this->csen('Program', 'Schedule'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            ':Default:Schedule:default',
        );

        if (TeamsPresenter::isVisible($this->gamePhaseCalculator)) {
            $items[] = new NavItem(
                new PageTitle($this->csen('Týmy', 'Teams'), 'visible-sm-inline glyphicon glyphicon-edit'),
                ':Default:Teams:',
            );
        }

        if (RegistrationPresenter::isVisible($this->gamePhaseCalculator)) {
            $items[] = new NavItem(
                new PageTitle(
                    $this->csen('Registrace', 'Registration'),
                    'visible-sm-inline glyphicon glyphicon-edit'
                ),
                ':Default:Registration:',
            );
        }

        return $items;
    }
}
