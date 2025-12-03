<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

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
        return !$this->getNewestEvent()->getRegistrationPeriod()->is(Phase::before);
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function actionDefault(): void
    {
        if (!self::isVisible()) {
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
