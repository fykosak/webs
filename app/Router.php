<?php

declare(strict_types=1);

namespace App;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class Router
{

    /**
     * Creates a global filter for a route modifying parameters to use domains instead of lang.
     */
    private static function useTranslateFilter(?array $domainList, array $routerMapping): array
    {
        return [
            // TRANSLATE [domain, presenter, action] TO [language, presenter, action]
            Route::FILTER_IN => function (array $params) use ($routerMapping, $domainList): array {
                // From where to extract the language
                if ($domainList && count($domainList)) {
                    $domainLang = $domainList[$params['domain']] ?? null;
                    // In case of accessing from unknown domain (which should not happen)
                    if ($domainLang === null) {
                        trigger_error(
                            'Domain \'' . $params['domain'] . '\' has no language assigned. Fallback to en.',
                            E_USER_WARNING
                        );
                        $domainLang = 'en';
                    }

                    // Set the language guessed from the domain
                    $params['lang'] = $domainLang;
                } else {
                    if (!isset($params['lang'])) {
                        $params['lang'] = 'en';
                    }
                }

                // Translate presenter
                if (
                    isset($routerMapping[$params['lang']]) &&
                    isset($routerMapping[$params['lang']][$params['presenter']])
                ) {
                    $params['presenter'] = $routerMapping[$params['lang']][$params['presenter']];
                }

                return $params;
            },

            // From params to URL
            Route::FILTER_OUT => function (array $params) use ($routerMapping, $domainList): array {
                // Always translate presenter based on language

                if (isset($routerMapping[$params['lang']])) {
                    $key = array_search($params['presenter'], $routerMapping[$params['lang']]);
                    if ($key !== false) {
                        // Return the FIRST occurrence
                        $params['presenter'] = $key;
                    }
                }

                // Either set the language in the domain, or in lang parameter

                if ($domainList && count($domainList)) {
                    $params['domain'] = array_search($params['lang'], $domainList);
                    if ($params['domain'] === false) {
                        return [];
                    }

                    unset($params['lang']);
                } else {
                    $params['domain'] = $_SERVER['HTTP_HOST'];
                    // Keep the lang
                }

                return $params;
            },
        ];
    }

    public static function createRouter(?array $domainList, array $routerMapping): \Nette\Routing\Router
    {
        $router = new RouteList();

        $router->withModule('Archive')
            ->addRoute('//<domain>/<eventYear ([0-9]{4})(-.*)?>/[<presenter>/[<action>]]', [
                'presenter' => 'Default',
                'action' => 'default',
                null => self::useTranslateFilter($domainList, $routerMapping['archive'])
            ]);

        $router->withModule('Default')
            ->addRoute('//<domain>/<presenter>[/<action>]', [
                'presenter' => 'Default',
                'action' => 'default',
                null => self::useTranslateFilter($domainList, $routerMapping['default'])
            ]);

        return $router;
    }
}
