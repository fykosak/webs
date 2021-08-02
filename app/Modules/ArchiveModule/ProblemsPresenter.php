<?php

declare(strict_types=1);

namespace App\Modules\ArchiveModule;

class ProblemsPresenter extends BasePresenter
{
    /**
     * @return void
     * @throws \Exception
     */
    public function renderDefault(): void
    {
        $this->setPageTitle(_('Problems'));
    }
}
