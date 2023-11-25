<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use App\Components\TeamList\TeamListComponent;
use App\Models\GamePhaseCalculator;
use Nette\Application\BadRequestException;

class TeamsPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    public static function isVisible(GamePhaseCalculator $gamePhaseCalculator): bool
    {
        return !$gamePhaseCalculator->isRegistration(GamePhaseCalculator::BEFORE);
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function actionDefault(): void
    {
        if (!self::isVisible($this->gamePhaseCalculator)) {
            $this->error();
        }
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentTeamList(): TeamListComponent
    {
        return new TeamListComponent($this->getContext(), $this->gamePhaseCalculator->getFKSDBEvent()->eventId);
    }
}
