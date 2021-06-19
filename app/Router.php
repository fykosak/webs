<?php

namespace App;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class Router {

    public static function createRouter(): \Nette\Routing\Router {
        $router = new RouteList();

        $router->withModule('Archive')
            ->addRoute('<eventYear ^([0-9]{4})(-[a-z]+)?$>[/<presenter>[/<action>]]', 'Default:default');
            
        $router->withModule('Default')
            ->addRoute('index.php', 'Default:default', $router::ONE_WAY)
            ->addRoute('<presenter>[/<action>]', 'Default:default')
            ->addRoute('team/[<action>/[<id>]]', 'Team:default');
        
        return $router;
    }
}

