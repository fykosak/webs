<?php

declare(strict_types=1);

namespace App\Models\OldFykos;

use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;

final class Jumbotron extends BaseComponent
{
    private static function getJumbotronSchema(): Schema
    {
        return Expect::listOf(
            Expect::structure([
                'headline' => Expect::string()->required(),
                'text' => Expect::string()->required(),
                'backgrounds' => Expect::structure([
                    'outer' => Expect::anyOf('fykos', 'fof', 'fol'),
                    'inner' => Expect::anyOf('fykos', 'fof', 'fol'),
                ])->castTo('array'),
                'buttons' => Expect::listOf(
                    Expect::structure([
                        'page' => Expect::string()->required(),
                        'title' => Expect::string()->required(),
                    ])->castTo('array')
                ),
            ])->castTo('array')
        );
    }

    public function render(string $lang = 'cs'): void
    {
        $this->template->items = $this->getItemsByPage($lang);
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'jumbotron.latte');
    }

    private static function getDataFromJSON(string $dataPage): ?array
    {

        $data = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . $dataPage . '.json'), true);
        if (!$data) {
            return null;
        }
        $processor = new Processor();
        $data = $processor->process(self::getJumbotronSchema(), $data);
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
