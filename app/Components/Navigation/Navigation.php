<?php

declare(strict_types=1);

namespace App\Components\Navigation;

use App\Modules\Core\Language;
use Fykosak\Utils\Components\DIComponent;
use Fykosak\Utils\UI\Navigation\NavItem;

class Navigation extends DIComponent
{
    private array $items = [];

    public function render(string $logoPath = null, string $logoAlt = null, bool $hasI18n = true): void
    {
        $this->template->logoPath = $logoPath;
        $this->template->logoAlt = $logoAlt;
        $this->template->items = $this->items;
        $this->template->lang = $this->translator->lang;
        $this->template->hasI18n = $hasI18n;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'navigation.latte');
    }

    public function addNavItem(NavItem $item): void
    {
        $this->items[] = $item;
    }

    public static function mapLangToIcon(Language $lang): string
    {
        return match ($lang) {
            Language::en => 'flag-icon flag-icon-us',
            Language::cs => 'flag-icon flag-icon-cz',
        };
    }
}
