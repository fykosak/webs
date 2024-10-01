<?php

declare(strict_types=1);

namespace App\Modules\Dsef\DefaultModule;

use App\Models\Downloader\EventModel;
use Nette\Application\BadRequestException;

class RegistrationPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    public static function isVisible(EventModel $event): bool
    {
        return $event->getRegistrationPeriod()->isOnGoing();
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
