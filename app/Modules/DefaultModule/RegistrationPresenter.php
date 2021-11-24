<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use App\Models\GamePhaseCalculator;
use Nette\Application\BadRequestException;

class RegistrationPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    public static function isVisible(GamePhaseCalculator $gamePhaseCalculator): bool
    {
        return $gamePhaseCalculator->isRegistration(GamePhaseCalculator::NOW);
    }

    /**
     * @throws BadRequestException|\Throwable
     */
    public function actionDefault()
    {
        if (!self::isVisible($this->gamePhaseCalculator)) {
            $this->error();
        }
    }
}
