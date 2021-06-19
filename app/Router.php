<?php

namespace App;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class Router {

    public static function createRouter(): \Nette\Routing\Router {
        $router = new RouteList();


        $router[] = new Route('<presenter>[/<action>]', [
            'module' => 'Default',
            'presenter' => 'Default',
            'action' => 'default',
        ]);
        
        $router[] = new Route('index.php', [
            'module' => 'Frontend',
            'presenter' => 'Default',
            'action' => 'default',
        ], Route::ONE_WAY);
        $router[] = new Route('<eventYear [0-9]+>[/<presenter>[/<action>]]', [
            'module' => 'Archive',
            'presenter' => 'Default',
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

