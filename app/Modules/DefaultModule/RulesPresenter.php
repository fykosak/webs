<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

class RulesPresenter extends BasePresenter
{

    public function renderDefault(): void
    {
        $this->setPagetitle(_('Brief Rules'));
    }

    public function renderComplete()
    {
        $this->setPagetitle(_('Full Rules'));
    }

    public function renderOrganisationalRegulations()
    {
        $this->setPagetitle(_('Organisational Regulations'));
    }
}
