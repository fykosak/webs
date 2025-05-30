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
            // results
            ['Default:Results', 'default'],
            // about us
            ['Default:About', 'default'],
            ['Default:About', 'organizers'],
            ['Default:About', 'history'],
            ['Default:About', 'sponsors'],
            ['Default:About', 'contact'],
            // how to solve
            ['Default:HowToSolve', 'default'],
            ['Default:HowToSolve', 'rules'],
            ['Default:HowToSolve', 'solutions'],
            ['Default:HowToSolve', 'experiments'],
            // events
            ['Default:Events', 'default'],
            ['Default:Events', 'dalsi'],
            // ['Default:Events', 'detail'],
            ['Default:Events', 'setkani'],
            ['Default:Events', 'tabor'],
            ['Default:Events', 'vikendovka'],
            // results
            ['Default:Results', 'default'],
            // teachers
            ['Default:Teachers', 'default'],
            // bingo
            ['Default:Bingo', 'default'],
            // separate pages
            // ['Default:Separate', 'serialArchive'],
            // titlepage
            // ['Default:Default', 'default'],
        ]);
    }
}

$testCase = new DefaultModule($container);
$testCase->run();
// phpcs:enable
