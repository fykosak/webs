<?php

declare(strict_types=1);

namespace App\Models\OldFykos;

use Fykosak\Utils\BaseComponent\BaseComponent;

final class Jumbotron extends BaseComponent
{
    public function render(string $lang = 'cs'): void
    {
        $this->template->items = $this->getItemsByPage('cs');
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'jumbotron.latte');
    }

    private static function getDataFromJSON(string $dataPage): ?array
    {

        $data = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . $dataPage . '.json'), true);
        if (!$data) {
            return null;
        }
        $items = [];
        foreach ($data as $datum) {
            $items[] = new  JumbotronItem($datum);
        }
        return $items;
    }

    /**
     * @return JumbotronItem[]|null
     */
    public function getItemsByPage(string $lang): ?array
    {
        switch ($lang) {
            case 'cs':
                return self::getDataFromJSON('jumbotron-cs');
            case 'en':
                return self::getDataFromJSON('jumbotron-en');
            default:
                return null;
        }
    }
}
