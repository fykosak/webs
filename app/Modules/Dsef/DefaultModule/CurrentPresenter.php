<?php

declare(strict_types=1);

namespace App\Modules\Dsef\DefaultModule;

use App\Models\GamePhaseCalculator;
use Nette\Application\BadRequestException;

class CurrentPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    public static function isVisible(GamePhaseCalculator $gamePhaseCalculator): bool
    {
        //return $gamePhaseCalculator->isRegistration(GamePhaseCalculator::BEFORE);
		return true;
    }

    /**
     * @throws BadRequestException|\Throwable
     */
    public function actionDefault(): void
    {
        if (!self::isVisible($this->gamePhaseCalculator)) {
            $this->error();
        }
    }
}
