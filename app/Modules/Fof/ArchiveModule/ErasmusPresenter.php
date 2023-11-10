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

     public function startup(): void
    {
        parent::startup();

        // Zkontrolujte, zda je eventYear nastaven na 2022
        if ($this->eventYear !== '2022') {
            throw new BadRequestException('Event not found', IResponse::S404_NOT_FOUND);
        }
    }
    
    protected function createComponentGallery(): ImageGalleryControl
    {
        return new ImageGalleryControl($this->context);
    }
}
