<?php

namespace App\Components\Navigation;

use Nette\SmartObject;

/**
 * Class NavItem
 * @author Michal Červeňák <miso@fykos.cz>
 */
class NavItem {
    use SmartObject;

    public string $destination;
    public array $linkParams;
    public string $title;
    public string $icon;

    public function __construct(string $destination, array $linkParams, string $title, string $icon = '') {
        $this->destination = $destination;
        $this->linkParams = $linkParams;
        $this->title = $title;
        $this->icon = $icon;
    }
}
