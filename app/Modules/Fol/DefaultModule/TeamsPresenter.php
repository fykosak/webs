<?php

declare(strict_types=1);

namespace App\Modules\Fol\DefaultModule;

use App\Components\TeamList\TeamListComponent;
use Fykosak\Utils\DateTime\Phase;
use Nette\Application\BadRequestException;

class TeamsPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    public function isVisible(): bool
    {
        $event = $this->getNewestEvent();
        return !$event->getRegistrationPeriod()->is(Phase::before)
            && !$event->getNearEventPeriod()->is(Phase::after);
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function actionDefault(): void
    {
        if (!$this->isVisible()) {
            $this->error();
        }
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentTeamList(): TeamListComponent
    {
        return new TeamListComponent($this->getContext(), $this->getNewestEvent()->eventId);
    }
}
