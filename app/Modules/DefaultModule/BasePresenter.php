<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use App\Components\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{

    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new PageTitle(_('about.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:AboutTheCompetition:default',
        );
        $items[] = new NavItem(
            new PageTitle(_('rules.menu'), 'visible-sm-inline glyphicon glyphicon-exclamation-sign'), // TODO
            ':Default:Rules:default',
        );
        $items[] = new NavItem(
            new PageTitle(_('faq.menu'), 'visible-sm-inline glyphicon glyphicon-question-sign'), // TODO
            ':Default:Faq:default',
        );
        $items[] = new NavItem(
            new PageTitle(_('howToPlay.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:HowToPlay:default',
        );
        $items[] = new NavItem(
            new PageTitle(_('schedule.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:Schedule:default',
        );
        $items[] = new NavItem(
            new PageTitle(_('reports.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:Reports:default',
        );
        $items[] = new NavItem(
            new PageTitle(_('archive.menu'), 'visible-sm-inline glyphicon glyphicon-compressed'), // TODO
            ':Default:Archive:default',
        );

        //if ($this->yearsService->isRegistrationStarted()) {
        //$items[] = new NavItem(':Default:Team:list', [], _('Týmy'), 'visible-sm-inline glyphicon glyphicon-list');
        //  if ($this->yearsService->isGameStarted()) {
        // $items[] = new NavItem(':Archive:Archive:results', [],
        // _('Výsledky'), 'visible-sm-inline glyphicon glyphicon-stats');
        // }
        //}

        // if ($this->yearsService->isRegistrationActive()) {
        //    if (!$this->getUser()->isLoggedIn()) {
        $items[] = new NavItem(
            new PageTitle(_('Registrace'), 'visible-sm-inline glyphicon glyphicon-edit'),
            ':Default:Registration:',
        );
        //    }
        // }
        return $items;
    }
}
