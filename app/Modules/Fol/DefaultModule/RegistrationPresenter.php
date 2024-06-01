<?php

declare(strict_types=1);

namespace App\Modules\Fol\DefaultModule;

use App\Models\NetteDownloader\ORM\Models\ModelEvent;
use Fykosak\Utils\DateTime\Phase;
use Nette\Application\BadRequestException;

class RegistrationPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    public static function isVisible(ModelEvent $event): bool
    {
        return $event->getRegistrationPeriod()->is(Phase::onGoing);
    }

    /**
     * @throws BadRequestException|\Throwable
     */
    public function actionDefault(): void
    {
        if (!self::isVisible($this->getNewestEvent())) {
            $this->error();
        }
    }
}
