<?php

declare(strict_types=1);

namespace App\Modules\DefaultModule;

use Fykosak\Utils\UI\PageTitle;

class RulesPresenter extends BasePresenter
{

    public function renderDefault(): void
    {
        $this->setPageTitle(new PageTitle(_('Brief Rules')));
    }

    public function renderComplete()
    {
        $this->setPageTitle(new PageTitle(_('Full Rules')));
    }

    public function renderOrganisationalRegulations()
    {
        $this->setPageTitle(new PageTitle(_('Organisational Regulations')));
    }
}
