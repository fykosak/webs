<?php

namespace App\Model\Translator;

use Nette\Localization\ITranslator;

/**
 *
 * @author Michal Koutný <xm.koutny@gmail.com>
 */
class GettextTranslator implements ITranslator {

    public static array $locales = [
        'cs' => 'cs_CZ.utf-8',
        'en' => 'en_US.utf-8',
        /* 'sk' => 'sk_SK.utf-8',
          'hu' => 'hu_HU.utf-8',
          'pl' => 'pl_PL.utf-8',
          'ru' => 'ru_RU.utf-8',*/
    ];

    public function translate($message, ...$parameters): string {
        if ($message === '' || $message === null) {
            return '';
        }
        if (isset($parameters[0])) {
            return ngettext($message, $message, (int)$parameters[0]);
        } else {
            return gettext($message);
        }
    }

    public static function i18nHelper($object, $field, $lang) {
        return $object->{$field . '_' . $lang};
    }

    public static function getSupportedLangs() {
        return array_keys(self::$locales);
    }
}
