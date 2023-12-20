<?php

declare(strict_types=1);

namespace App\Modules\Fykos\EventsModule;

class FykosPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->template->currentFYKOSYear = $this->currentFYKOSYear;
    }
}
