<?php

declare(strict_types=1);

namespace App\Components\Navigation;

use Fykosak\Utils\UI\PageTitle;
use Nette\SmartObject;

class NavItem
{
    use SmartObject;

    public string $destination;
    public array $linkParams;
    public PageTitle $title;

    public function __construct(PageTitle $title, string $destination, array $linkParams = [])
    {
        $this->destination = $destination;
        $this->linkParams = $linkParams;
        $this->title = $title;
    }
}
