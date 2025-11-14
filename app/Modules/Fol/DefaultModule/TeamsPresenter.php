<?php

declare(strict_types=1);

namespace App\Modules\Fol\DefaultModule;

use App\Components\TeamList\TeamListComponent;
use App\Models\Downloader\Models\EventModel;
use Fykosak\Utils\DateTime\Phase;
use Nette\Application\BadRequestException;

class TeamsPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    public static function isVisible(EventModel $event): bool
    {
        return !$event->getRegistrationPeriod()->is(Phase::before)
            && !$event->getNearEventPeriod()->is(Phase::after);
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function actionDefault(): void
    {
        if (!self::isVisible($this->getNewestEvent())) {
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
