<?php

declare(strict_types=1);

namespace App;

use Nette\Application\Routers\RouteList;

class FofRouter
{

    public static function createRouter(): \Nette\Routing\Router
    {
        $router = new RouteList();

        $router->withModule('Default')
            ->addRoute('//<domain>/<presenter>[/<action>]', [
                'presenter' => 'Default',
                'action' => 'default',
            ]);

        return $router;
    }
}
