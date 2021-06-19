<?php

namespace App;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class Router {

    public static function createRouter(): \Nette\Routing\Router {
        $router = new RouteList();

        $router->addRoute('<eventYear 20[0-9]{2}(-[a-z]+)?>/<presenter>[/<action default>]', [
            'module' => 'Archive',
        ]);

        $router->withModule('Archive')
            ->addRoute('<eventYear ^([0-9]{4})(-[a-z]+)?$>[/<presenter>[/<action>]]', 'Default:default');

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
            ])
            ->addRoute('team/[<action>/[<id>]]', 'Team:default');

        return $router;
    }
}

