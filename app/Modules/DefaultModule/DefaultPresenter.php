<?php

namespace App\Modules\DefaultModule;

use Exception;
use Nette;

class DefaultPresenter extends BasePresenter {

    /**
     * @return void
     * @throws Exception
     */
    public function renderDefault(): void {
        $this->setPagetitle(_('Mezinárodní soutež ve fyzice'));
     //   $this->template->year = $this->yearsService->findCurrent();
        $this->changeViewByLang();
    }

    public function renderLastYears(): void {
        $this->setPagetitle(_('Minulé ročníky'));
        $this->changeViewByLang();
    }

    public function renderRules(): void {
        $this->setPagetitle(_('Pravidla'));
        $this->changeViewByLang();
    }

    public function renderHowto(): void {
        $this->setPagetitle(_('Rychlý grafický návod ke hře'));
        $this->changeViewByLang();
    }

    public function renderList(): void {
        $this->setPageTitle(_('Přihlášené týmy'));
        $this->changeViewByLang();
    }

    public function renderTaskExamples(): void {
        $this->setPagetitle(_('Rozcvička'));
    }

    public function renderOtherEvents(): void {
        $this->setPagetitle(_('Další akce'));
        $this->changeViewByLang();
    }


}
