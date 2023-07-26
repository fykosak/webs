<?php

declare(strict_types=1);

namespace Tests\PresentersTests\PageDisplay\Fof;

use Tests\PresentersTests\PageDisplay\AbstractPageDisplayTestCase;

// phpcs:disable
define("MODULE_NAME", "fof");
$container = require '../../../Bootstrap.php';

// phpcs:enable
class DefaultModule extends AbstractPageDisplayTestCase
{

    public function getPages(): array
    {
        return self::getPageLangVariants([
            ['Default:AboutTheCompetition', 'default'],
            ['Default:AboutTheCompetition', 'organizers'],
            ['Default:Accommodation', 'default'],
            ['Default:Archive', 'default'],
            ['Default:Default', 'default'],
            ['Default:Erasmus', 'default'],
            ['Default:History', 'default'],
            ['Default:Merch', 'default'],
            ['Default:Partners', 'default'],
            //['Default:Registration', 'default'],
            ['Default:Rules', 'complete'],
            ['Default:Rules', 'default'],
            ['Default:Rules', 'organizationalRegulations'],
            ['Default:Rules', 'resolutions'],
            ['Default:Schedule', 'default'],
            ['Default:Schedule', 'detail'],
            ['Default:Scholarship', 'default'],
            ['Default:Scholarship', 'report'],
            //['Default:Teams', 'default']
        ]);
    }
}

// phpcs:disable
$testCase = new DefaultModule($container);
$testCase->run();
// phpcs:enable
