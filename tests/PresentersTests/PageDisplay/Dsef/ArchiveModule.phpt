<?php

declare(strict_types=1);

namespace Tests\PresentersTests\PageDisplay\Dsef;

use Nette\Utils\Finder;
use Tester\Assert;
use Tests\PresentersTests\PageDisplay\AbstractPageDisplayTestCase;

// phpcs:disable
const MODULE_NAME = 'dsef';
$container = require '../../../Bootstrap.php';

// phpcs:enable
class ArchiveModule extends AbstractPageDisplayTestCase
{
    public function getPages(): array
    {
        $pages = [];
        foreach (
            Finder::findFiles('simple.*.latte')->from(
                __DIR__ . '/../../../../app/Modules/Dsef/ArchiveModule/templates/Default'
            ) as $filename => $file
        ) {
            $eventKey = basename($filename, '.latte');
            $eventKey = substr($eventKey, strlen('simple.'));
            $params = explode('-', $eventKey);
            Assert::count(2, $params, 'Event key in filename not valid');
            [$eventYear, $eventMonth] = $params;
            $pages[] = ['Archive:Default', 'default', ['eventYear' => $eventYear, 'eventMonth' => $eventMonth]];
        }
        return $pages;
    }
}

// phpcs:disable
$testCase = new ArchiveModule($container);
$testCase->run();
// phpcs:enable
