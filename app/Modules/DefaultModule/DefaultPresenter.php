<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use App\Components\UpperHomeCountdown\UpperHomeCountdownComponent;
use App\Components\UpperHomeMap\UpperHomeCountdown;
use App\Components\UpperHomeMap\UpperHomeMapComponent;
use App\Models\GamePhaseCalculator;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\Localization\GettextTranslator;
use Fykosak\Utils\UI\PageTitle;

class DefaultPresenter extends BasePresenter
{

    public function renderDefault(): void
    {
        $this->setPageTitle(new PageTitle(_('Mezinárodní soutež ve fyzice')));
    }

    protected function createComponentUpperHomeMap(): UpperHomeMapComponent
    {
        return new UpperHomeMapComponent($this->getContext());
    }

    protected function createComponentUpperHomeCountdown(): UpperHomeCountdownComponent
    {
        return new UpperHomeCountdownComponent($this->gamePhaseCalculator);
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
