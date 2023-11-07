<?php

declare(strict_types=1);

namespace Tests\PresentersTests\PageDisplay\Fof;

use Tests\PresentersTests\PageDisplay\AbstractPageDisplayTestCase;

// phpcs:disable
define('MODULE_NAME', 'fof');
$container = require '../../../Bootstrap.php';

// phpcs:enable
class ArchiveModule extends AbstractPageDisplayTestCase
{
    protected function transformParams(string $presenterName, string $action, array $params): array
    {
        [$presenterName, $action, $params] = parent::transformParams($presenterName, $action, $params);
        $params['eventYear'] = '1970';
        return [$presenterName, $action, $params];
    }

    public function getPages(): array
    {
        return self::getPageLangVariants([
            ['Archive:Default', 'default'],
            ['Archive:DetailedResults', 'default'],
            ['Archive:Erasmus', 'default'],
            ['Archive:Erasmus', 'report'],
            //['Archive:Reports', 'default'],
            ['Archive:Results', 'default'],
            ['Archive:Teams', 'default'],
        ]);
    }
}

// phpcs:disable
$testCase = new ArchiveModule($container);
$testCase->run();
// phpcs:enable
