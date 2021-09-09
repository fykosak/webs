<?php

declare(strict_types=1);

namespace App\Components\UpperHomeCountdown;

use Fykosak\Utils\BaseComponent\BaseComponent;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventList;
use Nette\DI\Container;
use Nette\SmartObject;
use App\Models\GamePhaseCalculator;

class UpperHomeCountdownComponent extends BaseComponent
{
    protected GamePhaseCalculator $calculator;

    public function render(): void
    {
        $this->template->isBeforeRegistration = $this->isBeforeRegistration();
        $this->template->registrationOpen = $this->calculator->isRegistrationOpen();
        $this->template->isBeforeContest = $this->isBeforeContest();
        $this->template->registrationBegin = $this->getFKSDBEvent()->registrationBegin;
        $this->template->contestBegin = $this->getFKSDBEvent()->begin;
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomeCountdown.latte');
    }

    public function isBeforeRegistration(): bool
    {
        return new \DateTime() < $this->getFKSDBEvent()->registrationBegin;
    }

    public function isBeforeContest(): bool
    {
        return new \DateTime() < $this->getFKSDBEvent()->begin;
    }
}
