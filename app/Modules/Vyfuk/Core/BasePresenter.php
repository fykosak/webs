<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\Core;

use App\Models\Downloader\ContestModel;
use App\Models\Downloader\ContestRequest;
use App\Models\Downloader\ContestYearModel;
use App\Models\Downloader\DummyService;
use DateTime;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    private readonly DummyService $dummyService;

    public function injectDummyService(DummyService $dummyService): void
    {
        $this->dummyService = $dummyService;
    }
    /**
     * @return NavItem[]
     */
    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new PageTitle('O Nás'),
            ':default',
            [],
            [
                new NavItem(new PageTitle('Co je Výfuk?'), ':Default:About:default'),
                new NavItem(new PageTitle('Historie'), ':Default:About:history'),
                new NavItem(new PageTitle('Organizátoři'), ':Default:About:organizers'),
                new NavItem(new PageTitle('Podpořte nás'), ':Default:About:sponsors'),
                new NavItem(new PageTitle('Kontakt'), ':Default:About:contact'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle('TODO název'), //TODO název sekce
            ':default',
            [],
            [
                new NavItem(new PageTitle('Pravidla'), ':Default:Section:rules'),
                new NavItem(new PageTitle('Jak se zapojit'), ':Default:Section:howToEngage'),
                new NavItem(new PageTitle('Pro učitele'), ':Default:Section:teachers'),
                new NavItem(new PageTitle('Rady a tipy'), ':Default:Section:tips'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle('Zadání', 'bi bi-pencil-fill'),
            ':Default:Problems:default'
        );

        $items[] = new NavItem(
            new PageTitle('Pořadí'),
            ':Default:Results:default'
        );

        $items[] = new NavItem(
            new PageTitle('Akce'),
            ':Default:Events:default',
        );

        $items[] = new NavItem(
            new PageTitle('Archiv'),
            ':default',
            [],
            [
                new NavItem(new PageTitle('Úlohy'), ':Default:Archive:default'),
                new NavItem(new PageTitle('Výfučtení'), ':Default:Archive:serials'),
            ],
        );

        $items[] = new NavItem(
            new PageTitle('Přihlásit se'),
            'https://db.fykos.cz'
        );

        return $items;
    }

    protected function localize(): void
    {
        $this->lang = 'cs';
        parent::localize();
    }

    public function getCurrentYear(): ?ContestYearModel
    {
        $contest = $this->getContest();
        foreach ($contest->years as $year) {
            if ($year->active) {
                return $year;
            }
        }
        return null;
    }

    public function getContest(): ContestModel
    {
        return $this->dummyService->getFlat(new ContestRequest(2), ContestModel::class);
    }
}
