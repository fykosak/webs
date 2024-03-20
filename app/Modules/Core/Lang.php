<?php
declare(strict_types=1);

namespace App\Modules\Core;

use Fykosak\Utils\Localization\LangEnum;
use Nette\Utils\Html;

enum Lang: string implements LangEnum
{
    case CS = 'cs';
    case EN = 'en';

    public function badge(): Html
    {
        return Html::el('span')->addAttributes(['class' => 'badge bg-primary'])->addText($this->label());
    }

    public function label(): string
    {
        return match ($this) {
            self::CS => _('Czech'),
            self::EN => _('English'),
        };
    }

    public static function getLocales(): array
    {
        return [
            self::CS->value => 'cs_CZ.utf-8',
            self::EN->value => 'en_US.utf-8',
        ];
    }

    public function getLocaleDir(): string
    {
        return '';
    }

    public function getLocale(): string
    {
        return match ($this) {
            self::CS => 'cs_CZ.utf-8',
            self::EN => 'en_US.utf-8',
        };
    }
}
