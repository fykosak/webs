<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

class StartPresenter extends BasePresenter
{
    protected function includeJumbotron(): bool
    {
        return true;
    }
}
