<?php

declare(strict_types=1);

namespace App\Modules\Fykos\Core;

use App\Models\OldFykos\BootstrapNavBar;
use App\Models\OldFykos\Jumbotron;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    protected function includeJumbotron(): bool
    {
        return true;
    }

    protected function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->jumbotron = $this->includeJumbotron();
    }

    protected function createComponentJumbotron(): Jumbotron
    {
        return new Jumbotron($this->getContext());
    }

}
