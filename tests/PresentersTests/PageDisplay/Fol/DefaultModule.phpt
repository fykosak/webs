<?php

declare(strict_types=1);

namespace Tests\PresentersTests\PageDisplay\Fol;

use Tests\PresentersTests\PageDisplay\AbstractPageDisplayTestCase;

// phpcs:disable
const MODULE_NAME = 'fol';
$container = require '../../../Bootstrap.php';

// phpcs:enable
class DefaultModule extends AbstractPageDisplayTestCase
{
    public function getPages(): array
    {
        return self::getPageLangVariants([
            ['Default:AboutTheCompetition', 'default'],
            ['Default:Archive', 'default'],
            ['Default:Cooperation', 'default'],
            ['Default:Default', 'default'],
            ['Default:Default', 'lastYears'],
            ['Default:Default', 'otherEvents'],
            ['Default:Faq', 'default'],
            ['Default:FyziklaniInternational', 'default', ['lang' => 'cs']],
            ['Default:HowToPlay', 'default'],
            //['Default:Registration', 'default'],
            ['Default:Reports', 'default'],
            ['Default:Rules', 'complete'],
            ['Default:Rules', 'default'],
            ['Default:Rules', 'organisationalRegulations'],
            ['Default:Schedule', 'default'],
            //['Default:Teams', 'default']
        ]);
    }
}

// phpcs:disable
$testCase = new DefaultModule($container);
$testCase->run();
// phpcs:enable
