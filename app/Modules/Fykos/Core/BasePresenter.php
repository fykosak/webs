<?php

declare(strict_types=1);

namespace App\Modules\Fykos\Core;

use App\Models\Downloader\ContestModel;
use App\Models\Downloader\ContestRequest;
use App\Models\Downloader\ContestYearModel;
use App\Models\Downloader\FKSDBDownloader;
use App\Models\NetteDownloader\ORM\Services\DummyService;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;
use Nette\Utils\DateTime;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    protected readonly FKSDBDownloader $downloader;
    protected readonly DummyService $dummyService;

    final public function inject(FKSDBDownloader $downloader, DummyService $dummyService): void
    {
        $this->downloader = $downloader;
        $this->dummyService = $dummyService;
    }

    public function getCurrentYear(): ?ContestYearModel
    {
        $contest = $this->getContest();
        foreach ($contest->years as $year) {
            if ($year->begin < new DateTime() && $year->end > new DateTime()) {
                return $year;
            }
        }
        return null;
    }

    public function getContest(): ContestModel
    {
        return $this->dummyService->getFlat(new ContestRequest(1), ContestModel::class);
    }
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
                new NavItem(new PageTitle($this->csen('Organizátoři', 'Organizers')), ':Default:About:organizers'),
                new NavItem(new PageTitle($this->csen('Historie', 'History')), ':Default:About:history'),
                new NavItem(new PageTitle($this->csen('Kontakt', 'Contact')), ':Default:About:contact'),
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
            ),
            ':Events:Fykos:',
            [],
            [
                new NavItem(new PageTitle($this->csen('Základní informace', 'Basic Information')), ':Events:Fykos:'),
                new NavItem(new PageTitle($this->csen('Pravidla', 'Rules')), ':Events:Fykos:rules'),
                new NavItem(
                    new PageTitle($this->csen('Jak psát řešení', 'How to Write Solutions')),
                    ':Events:Fykos:texTutorial'
                ),
                new NavItem(
                    new PageTitle($this->csen('Jak na experimenty', 'How to Do Experiments')),
                    ':Events:Fykos:experiments'
                ),
            ],
        );

        $items[] = new NavItem(
            new PageTitle($this->csen('Zadání', 'Problems'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            ':Default:Problems:default',
            // @phpstan-ignore-next-line
            [
                'year' => null,
                'series' => null
            ]
        );

        $items[] = new NavItem(
            new PageTitle($this->csen('Pořadí', 'Results'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            ':Default:Results:default',
            // @phpstan-ignore-next-line
            [
                'year' => null
            ]
        );

        // $items[] = new NavItem(
        // new PageTitle( $this->csen('Archiv úloh', 'Problem Archive'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
        //     ':Default:ProblemsArchive:',
        // );

        $items[] = new NavItem(
            new PageTitle($this->csen('Přihlásit se', 'Sign In'), 'visible-sm-inline glyphicon glyphicon-info-sign'),
            'https://db.fykos.cz',
        );
        return $items;
    }

    protected function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->currentYear = $this->getCurrentYear();
    }
}
