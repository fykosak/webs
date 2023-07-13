<?php

declare(strict_types=1);

namespace Tests\PresentersTests\PageDisplay\Fol;

use Tests\PresentersTests\PageDisplay\AbstractPageDisplayTestCase;

// phpcs:disable
define("MODULE_NAME", "dsef");
$container = require '../../../Bootstrap.php';

// phpcs:enable
class DefaultModule extends AbstractPageDisplayTestCase
{
    public function getPages(): array
    {
        return [
            ['Default:Archive', 'default'],
            ['Default:Current', 'default'],
            ['Default:Default', 'default'],
            //['Default:Registration', 'default']
        ];
    }
}

// phpcs:disable
$testCase = new DefaultModule($container);
$testCase->run();
// phpcs:enable
