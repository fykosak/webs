<?php

declare(strict_types=1);

namespace App\Modules\Dsef\DefaultModule;

use App\Components\UpperHomePrague\UpperHomePrague;

class DefaultPresenter extends BasePresenter
{
    /**
     * @throws \Throwable
     */
    protected function createComponentPrague(): UpperHomePrague
    {
        return new UpperHomePrague($this->getContext(), $this->getNewestEvent());
    }
}
