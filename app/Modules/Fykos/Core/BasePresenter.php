<?php

declare(strict_types=1);

namespace App\Modules\Fykos\Core;

use App\Models\OldFykos\Jumbotron;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    public const CURRENT_YEAR = 37; // TODO: get from db

    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new PageTitle(null, 'O nás', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:About:',
            [],
            [
                new NavItem(new PageTitle(null, 'Co je FYKOS?'), ':Default:About:default'),
                new NavItem(new PageTitle(null, 'Organizátoři'), ':Default:About:Organizers'),
                new NavItem(new PageTitle(null, 'Historie'), ':Default:About:History'),
                new NavItem(new PageTitle(null, 'Kontakt'), ':Default:About:Contact')
            ],
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Akce', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Events:Default:',
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Seminář', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Events:Fykos:',
            [],
            [
                new NavItem(new PageTitle(null, 'Základní informace'), ':Events:Fykos:'),
                new NavItem(new PageTitle(null, 'Pravidla'), ':Events:Fykos:Rules'),
                new NavItem(new PageTitle(null, 'Jak psát řešení'), ':Events:Fykos:TexTutorial'),
                new NavItem(new PageTitle(null, 'Jak na experimenty'), ':Events:Fykos:Experiments')
            ],
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Zadání', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:Problems:',
        );

        $items[] = new NavItem(
            new PageTitle(null, 'Pořadí', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:Results:',
        );

        // $items[] = new NavItem(
        //     new PageTitle(null, 'Archiv úloh', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
        //     ':Default:ProblemsArchive:',
        // );

        $items[] = new NavItem(
            new PageTitle(null, 'Přihlásit se', 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            'https://db.fykos.cz',
        );
        return $items;
    }

    protected function includeJumbotron(): bool
    {
        return true;
    }

    protected function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->currentYear = self::CURRENT_YEAR;
        $this->template->jumbotron = $this->includeJumbotron();
    }

    protected function createComponentJumbotron(): Jumbotron
    {
        return new Jumbotron($this->getContext());
    }
}
