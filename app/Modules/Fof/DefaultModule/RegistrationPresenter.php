<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use Fykosak\Utils\DateTime\Phase;
use Nette\Application\BadRequestException;

class RegistrationPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    public function isVisible(): bool
    {
        return $this->getNewestEvent()->getRegistrationPeriod()->is(Phase::onGoing);
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
