<?php

declare(strict_types=1);

namespace Tests\PresentersTests\PageDisplay\Vyfuk;

use App\Modules\Vyfuk\DefaultModule\AboutPresenter;
use Fykosak\Utils\UI\Navigation\NavItem;
use Tests\PresentersTests\PageDisplay\AbstractPageDisplayTestCase;

// phpcs:disable
define('MODULE_NAME', 'vyfuk');
$container = require '../../../Bootstrap.php';

// phpcs:enable
class DefaultModule extends AbstractPageDisplayTestCase
{
    public function getPages(): array
    {
        return self::getPageLangVariants((new TestModule())->getPages());
    }
}

class TestModule extends AboutPresenter
{
    private array $addresses = [];
    /**
     * @return NavItem[]
     */
    function getPages(): array
    {
        $this->ParseItems($this->getNavItems());
        return array_map(function ($item) {
            $parts = preg_split('/:/', $item);
            $action = $parts[count($parts) - 1];
            unset($parts[count($parts) - 1]);
            return [substr(join(':', $parts),1), $action];
        }, array_filter(
            $this->addresses,
            function ($a) {
                return str_starts_with($a, ':') && count(preg_split('/:/', $a)) > 2;
            }
        ));

    }
    function ParseItems(array $items)
    {
        foreach ($items as $item) {
            $this->addresses[] = $item->destination;
            $this->ParseItems($item->children);
        }
    }

}


// phpcs:disable
$testCase = new DefaultModule($container);
$testCase->run();
// phpcs:enable
