<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use App\Models\GamePhaseCalculator;

class RegistrationPresenter extends BasePresenter
{
    public static function isVisible(GamePhaseCalculator $gamePhaseCalculator): bool
    {
        return $gamePhaseCalculator->isRegistration(GamePhaseCalculator::BEFORE) &&
            $gamePhaseCalculator->isGame(GamePhaseCalculator::BEFORE);
    }

    public function actionDefault()
    {
        if (!self::isVisible($this->gamePhaseCalculator)) {
            $this->error();
        }
    }
}
