<?php

declare(strict_types=1);

namespace App\Modules\Dsef\DefaultModule;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Dsef\Core\BasePresenter
{

    /**
     * @throws \Throwable
     */
    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new PageTitle(null, _('about.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            ':Default:AboutTheCompetition:default',
        );
        $items[] = new NavItem(
            new PageTitle(null, _('history.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            ':Default:History:default',
        );
        $items[] = new NavItem(
            new PageTitle(null, _('rules.menu'), 'visible-sm-inline glyphicon glyphicon-exclamation-sign'),
            ':Default:Rules:default',
        );
        $items[] = new NavItem(
            new PageTitle(null, _('accommodation.menu'), 'visible-sm-inline glyphicon glyphicon-question-sign'),
            ':Default:Accommodation:default',
        );
        $items[] = new NavItem(
            new PageTitle(null, _('schedule.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            ':Default:Schedule:default',
        );

        if (TeamsPresenter::isVisible($this->gamePhaseCalculator)) {
            $items[] = new NavItem(
                new PageTitle(null, _('teams.menu'), 'visible-sm-inline glyphicon glyphicon-edit'),
                ':Default:Teams:',
            );
        }

        if (RegistrationPresenter::isVisible($this->gamePhaseCalculator)) {
            $items[] = new NavItem(
                new PageTitle(null, _('registration.menu'), 'visible-sm-inline glyphicon glyphicon-edit'),
                ':Default:Registration:',
            );
        }

        return $items;
    }
}
