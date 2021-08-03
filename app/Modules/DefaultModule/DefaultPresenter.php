<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

class DefaultPresenter extends BasePresenter
{

    public function renderDefault(): void
    {
        $this->setPageTitle(_('Mezinárodní soutež ve fyzice'));
    }

    public function renderLastYears(): void
    {
        $this->setPageTitle(_('Minulé ročníky'));
    }

    public function renderRules(): void
    {
        $this->setPageTitle(_('Pravidla'));
    }

    public function renderHowto(): void
    {
        $this->setPageTitle(_('Rychlý grafický návod ke hře'));
    }

    public function renderList(): void
    {
        $this->setPageTitle(_('Přihlášené týmy'));
    }

    public function renderTaskExamples(): void
    {
        $this->setPageTitle(_('Rozcvička'));
    }

    public function renderOtherEvents(): void
    {
        $this->setPageTitle(_('Další akce'));
    }
}
