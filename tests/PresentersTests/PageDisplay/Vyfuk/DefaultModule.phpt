<?php

// phpcs:disable
declare(strict_types=1);

namespace Tests\PresentersTests\PageDisplay\Vyfuk;

use Tests\PresentersTests\PageDisplay\AbstractPageDisplayTestCase;

define('MODULE_NAME', 'vyfuk');
$container = require '../../../Bootstrap.php';

class DefaultModule extends AbstractPageDisplayTestCase
{
    public function getPages(): array
    {
        return self::getPageLangVariants([
            // tasks
            // ['Default:Problems', 'default'],
            ['Default:Problems', 'bingo'],
            // results
            ['Default:Results', 'default'],
            // about us
            ['Default:About', 'default'],
            ['Default:About', 'organizers'],
            ['Default:About', 'history'],
            ['Default:About', 'sponsors'],
            ['Default:About', 'contact'],
            // how to engage
            ['Default:Section', 'howToEngage'],
            ['Default:Section', 'rules'],
            ['Default:Section', 'howToSolve'],
            ['Default:Section', 'howToExperiment'],
            ['Default:Section', 'teachers'],
            // events
            ['Default:Events', 'default'],
        ]);
    }
}

$testCase = new DefaultModule($container);
$testCase->run();
// phpcs:enable
