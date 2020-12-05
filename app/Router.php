<?php

namespace App;

use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class Router {

    public static function createRouter(): IRouter {
        $router = new RouteList();

        $router[] = new Route('index.php', [
            'module' => 'Frontend',
            'presenter' => 'Default',
            'action' => 'default',
        ], Route::ONE_WAY);
        $router[] = new Route('year<year [0-9]+>/<action>', [
            'module' => 'Default',
            'presenter' => 'Archive',
            'action' => 'default',
        ]);
        $router[] = new Route('team/[<action>/[<id>]]', [
            'module' => 'Default',
            'presenter' => 'Team',
            'action' => 'default',
        ]);
        $router[] = new Route('[<action>/[<id>]]', [
            'module' => 'Default',
            'presenter' => 'Default',
            'action' => 'default',
        ]);
        return $router;
    }
}

