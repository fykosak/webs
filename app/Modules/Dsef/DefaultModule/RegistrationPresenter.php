<?php

declare(strict_types=1);

namespace App\Modules\Dsef\DefaultModule;

use Nette\Application\BadRequestException;

class RegistrationPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    public function isVisible(): bool
    {
        return $this->getNewestEvent()->getRegistrationPeriod()->isOnGoing();
    }

    /**
     * @throws BadRequestException|\Throwable
     */
    public function actionDefault(): void
    {
        if (!self::isVisible()) {
            $this->error();
        }
    }
}
