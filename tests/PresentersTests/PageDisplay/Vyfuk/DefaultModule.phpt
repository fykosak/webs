<?php

// phpcs:disable
declare(strict_types=1);

namespace Tests\PresentersTests\PageDisplay\Vyfuk;

use App\Modules\Vyfuk\DefaultModule\AboutPresenter;
use Fykosak\Utils\UI\Navigation\NavItem;
use Tests\PresentersTests\PageDisplay\AbstractPageDisplayTestCase;

define('MODULE_NAME', 'vyfuk');
$container = require '../../../Bootstrap.php';

class DefaultModule extends AbstractPageDisplayTestCase
{
    public function getPages(): array
    {
        return self::getPageLangVariants((new TestModule())->getPages());
    }
}

class TestModule extends AboutPresenter
{
    const ignoredPages = [
        ':Default:Problems:default'
    ];

    /**
     * @return NavItem[]
     */
    public function getPages(): array
    {
        return array_map(function ($item) {
            $parts = preg_split('/:/', $item);
            $action = $parts[count($parts) - 1];
            unset($parts[count($parts) - 1]);
            return [substr(join(':', $parts), 1), $action];
        }, array_filter(
            $this->parseItems($this->getNavItems()),
            function ($presenterName) {
                return str_starts_with($presenterName, ':')
                    && count(preg_split('/:/', $presenterName)) > 2
                    && !in_array($presenterName, self::ignoredPages);
            }
        ));
    }
    public function parseItems(array $items)
    {
        $addresses = [];
        foreach ($items as $item) {
            $addresses[] = $item->destination;
            $this->parseItems($item->children);
        }

        return $addresses;
    }
}


$testCase = new DefaultModule($container);
$testCase->run();
// phpcs:enable
