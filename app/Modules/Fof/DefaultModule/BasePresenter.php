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
            new PageTitle(_('about.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            ':Default:AboutTheCompetition:default',
        );
        $items[] = new NavItem(
            new PageTitle(_('history.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            ':Default:History:default',
        );
        $items[] = new NavItem(
            new PageTitle(_('rules.menu'), 'visible-sm-inline glyphicon glyphicon-exclamation-sign'),
            ':Default:Rules:default',
        );
        $items[] = new NavItem(
            new PageTitle(_('accommodation.menu'), 'visible-sm-inline glyphicon glyphicon-question-sign'),
            ':Default:Accommodation:default',
        );
        $items[] = new NavItem(
            new PageTitle(_('schedule.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            ':Default:Schedule:default',
        );

        if (TeamsPresenter::isVisible($this->gamePhaseCalculator)) {
            $items[] = new NavItem(
                new PageTitle(_('teams.menu'), 'visible-sm-inline glyphicon glyphicon-edit'),
                ':Default:Teams:',
            );
        }

        if (RegistrationPresenter::isVisible($this->gamePhaseCalculator)) {
            $items[] = new NavItem(
                new PageTitle(_('registration.menu'), 'visible-sm-inline glyphicon glyphicon-edit'),
                ':Default:Registration:',
            );
        }

        return $items;
    }
}
