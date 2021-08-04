<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use Fykosak\Utils\UI\PageTitle;

class DefaultPresenter extends BasePresenter
{

    public function renderDefault(): void
    {
        $this->setPageTitle(new PageTitle(_('Mezinárodní soutež ve fyzice')));
    }

    public function renderLastYears(): void
    {
        $this->setPageTitle(new PageTitle(_('Minulé ročníky')));
    }

    public function renderRules(): void
    {
        $this->setPageTitle(new PageTitle(_('Pravidla')));
    }

    public function renderHowto(): void
    {
        $this->setPageTitle(new PageTitle(_('Rychlý grafický návod ke hře')));
    }

    public function renderList(): void
    {
        $this->setPageTitle(new PageTitle(_('Přihlášené týmy')));
    }

    public function renderTaskExamples(): void
    {
        $this->setPageTitle(new PageTitle(_('Rozcvička')));
    }

    public function renderOtherEvents(): void
    {
        $this->setPageTitle(new PageTitle(_('Další akce')));
    }
}
