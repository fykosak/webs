<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use App\Components\Navigation\NavItem;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{

    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            ':Default:AboutTheCompetition:default',
            [],
            _('O soutěži'),
            'visible-sm-inline glyphicon glyphicon-info-sign'
        );
        $items[] = new NavItem(
            ':Default:Archive:default',
            [],
            _('Archiv'),
            'visible-sm-inline glyphicon glyphicon-compressed'
        );
        $items[] = new NavItem(
            ':Default:Organisers:default',
            [],
            _('Organizátoři'),
            'visible-sm-inline glyphicon glyphicon-compressed'
        );
        $items[] = new NavItem(
            ':Default:Rules:default',
            [],
            _('Pravidla'),
            'visible-sm-inline glyphicon glyphicon-exclamation-sign'
        );
        $items[] = new NavItem(
            ':Default:Faq:default',
            [],
            _('FAQ'),
            'visible-sm-inline glyphicon glyphicon-question-sign'
        );
        $items[] = new NavItem(
            ':Default:HowToPlay:default',
            [],
            _('Návod'),
            'visible-sm-inline glyphicon glyphicon-info-sign'
        );
        $items[] = new NavItem(
            ':Default:Schedule:default',
            [],
            _('Program'),
            'visible-sm-inline glyphicon glyphicon-info-sign'
        );
        $items[] = new NavItem(
            ':Default:Reports:default',
            [],
            _('Reporty'),
            'visible-sm-inline glyphicon glyphicon-info-sign'
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
            ':Default:Default:default',
            [],
            _('Registrace'),
            'visible - sm - inline glyphicon glyphicon-edit'
        );
        //    }
        // }
        return $items;
    }
}
