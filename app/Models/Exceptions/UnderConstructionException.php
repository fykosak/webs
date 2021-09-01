<?php

declare(strict_types=1);

namespace App\Models\Exceptions;

use Nette\Application\BadRequestException;
use Nette\Http\IResponse;

class UnderConstructionException extends BadRequestException
{
    public function __construct()
    {
        // see https://stackoverflow.com/questions/4642923/http-status-code-for-temporarily-unavailable-pages
        parent::__construct(_('This page is under construction'), IResponse::S503_SERVICE_UNAVAILABLE);
    }
}
