<?php

declare(strict_types=1);

namespace Tests\PresentersTests\PageDisplay\Vyfuk;

use Tests\PresentersTests\PageDisplay\AbstractPageDisplayTestCase;

// phpcs:disable
define('MODULE_NAME', 'vyfuk');
$container = require '../../../Bootstrap.php';

// phpcs:enable
class DefaultModule extends AbstractPageDisplayTestCase
{

    public function getPages(): array
    {
        return self::getPageLangVariants([
            ['Default:Default', 'default'],
        ]);
    }
}

// phpcs:disable
$testCase = new DefaultModule($container);
$testCase->run();
// phpcs:enable
