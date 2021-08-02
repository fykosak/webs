<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use Exception;

class DefaultPresenter extends BasePresenter
{

    /**
     * @return void
     * @throws Exception
     */
    public function renderDefault(): void
    {
        $this->setPagetitle(_('Mezinárodní soutež ve fyzice'));
        //   $this->template->year = $this->yearsService->findCurrent();
    }

    public function renderLastYears(): void
    {
        $this->setPagetitle(_('Minulé ročníky'));
    }

    public function renderRules(): void
    {
        $this->setPagetitle(_('Pravidla'));
    }

    public function renderHowto(): void
    {
        $this->setPagetitle(_('Rychlý grafický návod ke hře'));
    }

    public function renderList(): void
    {
        $this->setPageTitle(_('Přihlášené týmy'));
    }

    public function renderTaskExamples(): void
    {
        $this->setPagetitle(_('Rozcvička'));
    }

    public function renderOtherEvents(): void
    {
        $this->setPagetitle(_('Další akce'));
    }
}
