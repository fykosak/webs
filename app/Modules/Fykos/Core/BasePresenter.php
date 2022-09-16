<?php

declare(strict_types=1);

namespace App\Modules\Fykos\Core;

use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;

abstract class BasePresenter extends \App\Modules\Core\BasePresenter
{
    protected function getNavItems(): array
    {
        return [];
    }
}
