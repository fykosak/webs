<?php

declare(strict_types=1);

namespace App\Modules\Fykos\Core;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    protected function getNavItems(): array
    {
        return [];
    }
}
