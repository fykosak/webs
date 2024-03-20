<?php

declare(strict_types=1);

namespace App\Modules\Fykos\Core;

use App\Models\OldFykos\Jumbotron;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    public const CURRENT_YEAR = 37; // TODO: get from db

    /**
     * @return NavItem[]
     */
    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new PageTitle($this->csen('O nás', 'About Us'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:About:',
            [],
            [
                new NavItem(new PageTitle($this->csen('Co je FYKOS?', 'What Is FYKOS?')), ':Default:About:default'),
                new NavItem(new PageTitle($this->csen('Organizátoři', 'Organizers')), ':Default:About:Organizers'),
                new NavItem(new PageTitle($this->csen('Historie', 'History')), ':Default:About:History'),
                new NavItem(new PageTitle($this->csen('Kontakt', 'Contact')), ':Default:About:Contact'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle($this->csen('Akce', 'Events'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Events:Default:',
        );

        $items[] = new NavItem(
            new PageTitle(
                $this->csen('Seminář', 'FYKOS Competition'),
                'visible-sm-inline glyphicon glyphicon-info-sign'
            ), // TODO
            ':Events:Fykos:',
            [],
            [
                new NavItem(new PageTitle($this->csen('Základní informace', 'Basic Information')), ':Events:Fykos:'),
                new NavItem(new PageTitle($this->csen('Pravidla', 'Rules')), ':Events:Fykos:Rules'),
                new NavItem(
                    new PageTitle($this->csen('Jak psát řešení', 'How to Write Solutions')),
                    ':Events:Fykos:TexTutorial'
                ),
                new NavItem(
                    new PageTitle($this->csen('Jak na experimenty', 'How to Do Experiments')),
                    ':Events:Fykos:Experiments'
                ),
            ],
        );

        $items[] = new NavItem(
            new PageTitle($this->csen('Zadání', 'Problems'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:Problems:',
        );

        $items[] = new NavItem(
            new PageTitle($this->csen('Pořadí', 'Results'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
            ':Default:Results:',
        );

        // $items[] = new NavItem(
        //     new PageTitle( $this->csen('Archiv úloh', 'Problem Archive'), 'visible-sm-inline glyphicon glyphicon-info-sign'), // TODO
        //     ':Default:ProblemsArchive:',
        // );

        $items[] = new NavItem(
            new PageTitle($this->csen('Přihlásit se', 'Sign In'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            // TODO
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
