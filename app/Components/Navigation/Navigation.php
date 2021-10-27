<?php

declare(strict_types=1);

namespace App\Components\Navigation;

use Fykosak\Utils\BaseComponent\BaseComponent;
use Fykosak\Utils\UI\Navigation\NavItem;

class Navigation extends BaseComponent
{
    private array $items = [];

    public function render(): void
    {
        $this->template->items = $this->items;
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->supportedLangs = $this->translator->getSupportedLanguages();
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
