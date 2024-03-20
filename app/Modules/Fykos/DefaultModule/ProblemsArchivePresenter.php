<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use Nette\Application\BadRequestException;
use Nette\Http\IResponse;

class ProblemsArchivePresenter extends BasePresenter
{
    /**
     * @throws BadRequestException
     */
    public function renderDefault(): void
    {
        throw new BadRequestException(
            $this->csen('Stránka nenalezena', 'Page not found'),
            IResponse::S404_NOT_FOUND
        );
    }
}
