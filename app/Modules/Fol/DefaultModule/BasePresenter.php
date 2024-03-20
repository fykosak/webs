<?php

declare(strict_types=1);

namespace App\Modules\Fol\DefaultModule;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;
use Nette\Application\UI\Template;

abstract class BasePresenter extends \App\Modules\Fol\Core\BasePresenter
{
    /**
     * @throws \Throwable
     */
    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new PageTitle(
                $this->csen('O soutěži', 'About'),
                'visible-sm-inline glyphicon glyphicon-info-sign'
            ), // TODO
            ':Default:AboutTheCompetition:default',
        );
        $items[] = new NavItem(
            new PageTitle(
                $this->csen('Pravidla', 'Rules'),
                'visible-sm-inline glyphicon glyphicon-exclamation-sign'
            ), // TODO
            ':Default:Rules:default',
        );
        $items[] = new NavItem(
            new PageTitle(
                $this->csen('FAQ', 'FAQ'),
                'visible-sm-inline glyphicon glyphicon-question-sign'
            ), // TODO
            ':Default:Faq:default',
        );
//        $items[] = new NavItem(
//            new PageTitle(_('howToPlay.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
//            ':Default:HowToPlay:default',
//        );
        $items[] = new NavItem(
            new PageTitle(
                $this->csen('Program', 'Schedule'),
                'visible-sm-inline glyphicon glyphicon-info-sign'
            ), // TODO
            ':Default:Schedule:default',
        );
//        $items[] = new NavItem(
//            new PageTitle(_('reports.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
//            ':Default:Reports:default',
//        );
        $items[] = new NavItem(
            new PageTitle(
                $this->csen('Archiv', 'History'),
                'visible-sm-inline glyphicon glyphicon-compressed'
            ),
            ':Default:Archive:default',
        );


        if (TeamsPresenter::isVisible($this->gamePhaseCalculator)) {
            $items[] = new NavItem(
                new PageTitle( $this->csen('Týmy', 'Teams'), 'visible-sm-inline glyphicon glyphicon-edit'),
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

    protected function createTemplate(): Template
    {
        $template = parent::createTemplate();
        $template->event = $this->gamePhaseCalculator->getFKSDBEvent();
        $template->eventKey = parent::createEventKey($this->gamePhaseCalculator->getFKSDBEvent());
        return $template;
    }
}
