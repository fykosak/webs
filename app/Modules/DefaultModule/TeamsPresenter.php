<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use App\Components\TeamList\TeamListComponent;
use App\Models\GamePhaseCalculator;

class TeamsPresenter extends BasePresenter
{
    public static function isVisible(GamePhaseCalculator $gamePhaseCalculator): bool
    {
        return $gamePhaseCalculator->isRegistration(GamePhaseCalculator::NOW) ||
            (
                $gamePhaseCalculator->isRegistration(GamePhaseCalculator::AFTER) &&
                $gamePhaseCalculator->isGame(GamePhaseCalculator::BEFORE)
            );
    }

    public function actionDefault()
    {
        if (!self::isVisible($this->gamePhaseCalculator)) {
            $this->error();
        }
    }

    protected function createComponentTeamList(): TeamListComponent
    {
        return new TeamListComponent($this->getContext(), $this->gamePhaseCalculator->getFKSDBEvent()->eventId);
    }
}
