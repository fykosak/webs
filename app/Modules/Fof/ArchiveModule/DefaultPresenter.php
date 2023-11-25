<?php

declare(strict_types=1);

namespace App\Modules\Fof\ArchiveModule;

use App\Components\ImageGallery\ImageGalleryControl;
use Nette\Application\BadRequestException;

class DefaultPresenter extends BasePresenter
{
    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    protected function createComponentGallery(): ImageGalleryControl
    {
        return new ImageGalleryControl($this->context);
    }
}
