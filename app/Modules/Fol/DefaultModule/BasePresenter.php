<?php

declare(strict_types=1);

namespace App\Modules\Fol\DefaultModule;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\Title;
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
            new Title(
                null,
                $this->csen('O soutěži', 'About'),
                'visible-sm-inline glyphicon glyphicon-info-sign'
            ), // TODO
            ':Default:AboutTheCompetition:default',
        );
        $items[] = new NavItem(
            new Title(
                null,
                $this->csen('Pravidla', 'Rules'),
                'visible-sm-inline glyphicon glyphicon-exclamation-sign'
            ), // TODO
            ':Default:Rules:default',
        );
        $items[] = new NavItem(
            new Title(
                null,
                $this->csen('FAQ', 'FAQ'),
                'visible-sm-inline glyphicon glyphicon-question-sign'
            ), // TODO
            ':Default:Faq:default',
        );

        // $items[] = new NavItem(
        //     new Title(null, _('howToPlay.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
        //     ':Default:HowToPlay:default',
        // );

        //$items[] = new NavItem(
        //    new Title(
        //        null,
        //        $this->csen('Program', 'Schedule'),
        //        'visible-sm-inline glyphicon glyphicon-info-sign'
        //    ), // TODO
        //    ':Default:Schedule:default',
        //);

        // $items[] = new NavItem(
        //     new Title(null, _('reports.menu'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
        //     ':Default:Reports:default',
        // );

        $items[] = new NavItem(
            new Title(
                null,
                $this->csen('Archiv', 'History'),
                'visible-sm-inline glyphicon glyphicon-compressed'
            ),
            ':Default:Archive:default',
        );


        if ($this->getPresenterByName('Default:Teams')->isVisible()) {
            $items[] = new NavItem(
                new Title(null, $this->csen('Týmy', 'Teams'), 'visible-sm-inline glyphicon glyphicon-edit'),
                ':Default:Teams:',
            );
        }

        if ($this->getPresenterByName('Default:Registration')->isVisible()) {
            $items[] = new NavItem(
                new Title(
                    null,
                    $this->csen('Registrace', 'Registration'),
                    'visible-sm-inline glyphicon glyphicon-edit'
                ),
                ':Default:Registration:',
            );
        }

        return $items;
    }

    protected function createTemplate(?string $class = null): Template
    {
        $template = parent::createTemplate($class);
        $template->event = $this->getNewestEvent();
        $template->eventKey = parent::createEventKey($this->getNewestEvent());
        return $template;
    }
}
