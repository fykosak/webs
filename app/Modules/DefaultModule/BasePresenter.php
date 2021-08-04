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
            _('O soutěži'),
            'visible-sm-inline glyphicon glyphicon-info-sign',
            ':Default:AboutTheCompetition:default',
        );
//        $items[] = new NavItem(
//            _('Organizátoři'),
//            'visible-sm-inline glyphicon glyphicon-compressed',
//            ':Default:Organisers:default',
//        );
        $items[] = new NavItem(
            _('Pravidla'),
            'visible-sm-inline glyphicon glyphicon-exclamation-sign',
            ':Default:Rules:default',
        );
        $items[] = new NavItem(
            _('Návod'),
            'visible-sm-inline glyphicon glyphicon-info-sign',
            ':Default:HowToPlay:default',
        );
        $items[] = new NavItem(
            _('FAQ'),
            'visible-sm-inline glyphicon glyphicon-question-sign',
            ':Default:Faq:default',
        );
        $items[] = new NavItem(
            _('Program'),
            'visible-sm-inline glyphicon glyphicon-info-sign',
            ':Default:Schedule:default',
        );
        $items[] = new NavItem(
            _('Reporty'),
            'visible-sm-inline glyphicon glyphicon-info-sign',
            ':Default:Reports:default',
        );
        $items[] = new NavItem(
            _('Archiv'),
            'visible-sm-inline glyphicon glyphicon-compressed',
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
            _('Registrace'),
            'visible-sm-inline glyphicon glyphicon-edit',
            ':Default:Default:default',
        );
        //    }
        // }
        return $items;
    }
}
