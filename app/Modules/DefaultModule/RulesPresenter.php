<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

class RulesPresenter extends BasePresenter
{

    public function renderDefault(): void
    {
        $this->setPageTitle(_('Brief Rules'));
    }

    public function renderComplete()
    {
        $this->setPageTitle(_('Full Rules'));
    }

    public function renderOrganisationalRegulations()
    {
        $this->setPageTitle(_('Organisational Regulations'));
    }
}
