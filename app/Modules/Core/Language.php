<?php

declare(strict_types=1);

namespace App\Modules\Core;

use Fykosak\Utils\Localization\LangEnum;
use Nette\Utils\Html;

enum Language: string implements LangEnum
{
    case cs = 'cs';
    case en = 'en';

    public function badge(): Html
    {
        return Html::el('span')->addAttributes(['class' => 'badge bg-primary'])->addText($this->label());
    }

    public function label(): string
    {
        return match ($this) {
            self::cs => _('Czech'),
            self::en => _('English'),
        };
    }

    public static function getLocales(): array
    {
        return [
            self::cs->value => 'cs_CZ.utf-8',
            self::en->value => 'en_US.utf-8',
        ];
    }

    public function getLocaleDir(): string
    {
        return '';
    }

    public function getLocale(): string
    {
        return match ($this) {
            self::cs => 'cs_CZ.utf-8',
            self::en => 'en_US.utf-8',
        };
    }
}
