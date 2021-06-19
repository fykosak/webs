<?php

namespace App;

use Nette\Application\BadRequestException;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class Router {

    /**
     * Creates a global filter for a route modifying parameters to use domains instead of lang.
     * @param array|null $domainList
     * @param array $routerMapping
     * @param string $key todo replace by an array of keys
     * @return \Closure[]
     */
    private static function useTranslateFilter(?array $domainList, array $routerMapping, string $key): array {
        return [
            Route::FILTER_IN => function (array $params) use($routerMapping, $domainList, $key): array {
                if ($domainList && count($domainList)) {
                    $domainLang = $domainList[$params['domain']] ?? null;
                    if ($domainLang === null) {
                        trigger_error('Domain \'' . $params['domain'] . '\' has no language assigned. Fallback to en.', E_USER_WARNING);
                        $domainLang = 'en';
                    }

                    if ($domainLang !== 'en') {
                        if (array_key_exists($params['presenter'], $routerMapping[$domainLang])) {
                            $params['lang'] = $domainLang;
                            $params[$key] = $routerMapping[$domainLang][$params[$key]];
                        } else {
                            // 404 because there is no translation
                            throw new BadRequestException(404);
                        }
                    }
                }
                return $params;
            },

            // From params to URL
            Route::FILTER_OUT => function (array $params) use($routerMapping, $domainList, $key): array {
                if ($domainList && count($domainList)) {
                    if ($params['lang'] !== 'en') {
                        $params[$key] = array_search($params[$key], $routerMapping[$params['lang']]);
                    }

                    $params['domain'] = array_search($params['lang'], $domainList);
                    if ($params['domain'] === false) {
                        // No suitable domain for specified lang
                        $params['domain'] = array_key_first($domainList);
                    }
                    unset($params['lang']);
                } else {
                    $params['domain'] = $_SERVER['HTTP_HOST'];
                }


                return $params;
            },
        ];
    }

    public static function createRouter(?array $domainList, array $routerMapping): \Nette\Routing\Router {
        $router = new RouteList();

        $router->withModule('Archive')
            ->addRoute('<eventYear ([0-9]{4})(-.*)?>/[<presenter>/[<action>]]', 'Default:default');

        $router->withModule('Default')
            ->addRoute('index.php', 'Default:default', $router::ONE_WAY)
            ->addRoute('//<domain>/<presenter>[/<action>]', [
                'presenter' => [
                    Route::VALUE => 'Default',
                    Route::FILTER_TABLE => [
                        'about' => 'AboutTheCompetition'
                    ]
                ],
                'action' => 'default',
                null => self::useTranslateFilter($domainList, $routerMapping, "presenter")
            ])
            ->addRoute('team/[<action>/[<id>]]', 'Team:default');

        return $router;
    }
}

