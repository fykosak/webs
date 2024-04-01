<?php

declare(strict_types=1);

namespace App\Modules\Fof\ArchiveModule;

use App\Components\ImageGallery\ImageGalleryControl;
use Nette\Application\BadRequestException;
use Nette\Http\IResponse;

class ErasmusPresenter extends BasePresenter
{
    /**
     * @throws BadRequestException
     * @throws \Throwable
     */

    public function startUp(): void
    {
        parent::startUp();

        // Check if it is the correct event year, otherwise throw 404
        if ($this->eventYear !== '2022') {
            throw new BadRequestException('Event not found', IResponse::S404_NOT_FOUND);
        }
    }

    protected function createComponentGallery(): ImageGalleryControl
    {
        return new ImageGalleryControl($this->getContext());
    }
}
