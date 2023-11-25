<?php

declare(strict_types=1);

namespace App\Components\Navigation;

use Fykosak\Utils\BaseComponent\BaseComponent;
use Fykosak\Utils\UI\Navigation\NavItem;

class Navigation extends BaseComponent
{
    private array $items = [];

    public function render(string $logoPath = null, string $logoAlt = null, bool $hasI18n = true): void
    {
        $this->template->logoPath = $logoPath;
        $this->template->logoAlt = $logoAlt;
        $this->template->items = $this->items;
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->hasI18n = $hasI18n;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'navigation.latte');
    }

    public function addNavItem(NavItem $item): void
    {
        $this->items[] = $item;
    }

    public static function mapLangToIcon(string $lang): string
    {
        switch ($lang) {
            case 'en':
                return 'flag-icon flag-icon-us';
            case 'cs':
                return 'flag-icon flag-icon-cz';
            default:
                return 'flag-icon flag-icon-' . $lang;
        }
    }
}
