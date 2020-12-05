<?php

namespace App\Components\Navigation;

use Fykosak\Utils\BaseComponent\BaseComponent;
use Fykosak\Utils\Localization\GettextTranslator;

/**
 * Class Navigation
 * @author Michal Červeňák <miso@fykos.cz>
 * @property GettextTranslator $translator
 */
class Navigation extends BaseComponent {
    private array $items = [];

    public function render(): void {
        $this->getTemplate()->setFile(__DIR__ . DIRECTORY_SEPARATOR . 'navigation.latte');
        $this->template->items = $this->items;
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->supportedLangs = $this->translator->getSupportedLanguages();
        parent::render();
    }

    public function addNavItem(NavItem $item): void {
        $this->items[] = $item;
    }

    public static function mapLangToIcon(string $lang): string {
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
