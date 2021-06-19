<?php

namespace App;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class Router {

    public static function createRouter(): \Nette\Routing\Router {
        $router = new RouteList();

        $router->withModule('Archive')
            ->addRoute('<eventYear ([0-9]{4})(-.*)?>/[<presenter>/[<action>]]', 'Default:default');

        $router->withModule('Default')
            ->addRoute('index.php', 'Default:default', $router::ONE_WAY)
            ->addRoute('<presenter>[/<action>]', [
                'presenter' => [
                    Route::VALUE => 'Default',
                    Route::FILTER_TABLE => [
                        'about' => 'AboutTheCompetition',
                    ],
                ],
                'action' => 'default',
            ]);

        return $router;
    }
}

