<?php

declare(strict_types=1);

namespace App\Modules\Fof\ArchiveModule;

use Nette\Application\BadRequestException;
use Nette\Http\IResponse;

class SchedulePresenter extends BasePresenter
{
    /**
     * @throws BadRequestException
     * @throws \Throwable
     */

    public function startUp(): void
    {
        parent::startUp();

        // Check if it is the correct event year, otherwise throw 404
        if ($this->eventYear !== '2023') {
            throw new BadRequestException('Event not found', IResponse::S404_NOT_FOUND);
        }
    }
}
