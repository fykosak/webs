<?php

declare(strict_types=1);

namespace App;

use Nette\Routing\Route;
use Nette\Application\Routers\RouteList;
use Nette\Routing\Router;

class RouterFactory
{
    /**
     * Ensures that the request came to domain from $languages array of languages. Rejects otherwise.
     */
    private static function havingDomainLanguage(array $languages, ?array $domainList): array
    {
        return [
            // todo fix code duplication
            Route::FILTER_IN => function (array $params) use ($languages, $domainList) {
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
                } else {
                    $domainLang = $params['lang'] ?? 'en';
                }

                if (in_array($domainLang, $languages)) {
                    return $params;
                } else {
                    return null;
                }
            },
        ];
    }

    /**
     * Creates a global filter for a route modifying parameters to use domains instead of lang.
     */
    private static function useTranslateFilter(?array $domainList, array $routerMapping): array
    {
        Debugger::barDump($domainList);
        return [
            // TRANSLATE [domain, presenter, action] TO [language, presenter, action]
            Route::FILTER_IN => function (array $params) use ($routerMapping, $domainList): array {
                // From where to extract the language
                if ($domainList && count($domainList)) {
                    $domainLang = $domainList[$params['domain']] ?? null;
                    // In case of accessing from unknown domain (which should not happen), dsef is an exception
                    if ($domainLang === null and ($params['domain'] !== 'dsef.cz' or $params['domain'] !== 'dsef.fykos.cz')) {
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

    public static function createFolRouter(?array $domainList, array $routerMapping): Router
    {
        $router = new RouteList();

        $router->withModule('Archive')
            ->addRoute('//<domain>/<eventYear ([0-9]{4})(-.*)?>/[<presenter>/[<action>]]', [
                'presenter' => 'Default',
                'action' => 'default',
                null => self::useTranslateFilter($domainList, $routerMapping['archive']),
            ]);

        $router->withModule('Default')
            ->addRoute('//<domain>/<presenter>[/<action>]', [
                'presenter' => 'Default',
                'action' => 'default',
                null => self::useTranslateFilter($domainList, $routerMapping['default']),
            ]);

        return $router;
    }

    public static function createFofRouter(?array $domainList, array $routerMapping): Router
    {
        $router = new RouteList();

        $router->withModule('Archive')
            ->addRoute('//<domain>/<eventYear ([0-9]{4})(-.*)?>/[<presenter>/[<action>]]', [
                'presenter' => 'Default',
                'action' => 'default',
                null => self::useTranslateFilter($domainList, $routerMapping['archive']),
            ]);

        $router->withModule('Default')
            ->addRoute('//<domain>/(international|erasmus)', [
                'presenter' => 'Erasmus',
                'lang' => 'en',
                null => self::havingDomainLanguage(['cs'], $domainList),
            ], $router::ONE_WAY)
            ->addRoute('//<domain>/<presenter>[/<action>]', [
                'presenter' => 'Default',
                'action' => 'default',
                null => self::useTranslateFilter($domainList, $routerMapping['default']),
            ]);

        return $router;
    }

    public static function createDsefRouter(?array $domainList, array $routerMapping): Router
    {
        $router = new RouteList();

        $router->withModule('Archive')
            ->addRoute('//<domain>/<eventYear ([0-9]{4})(-.*)?>/<eventMonth>/[<presenter>/[<action>]]', [
                'presenter' => 'Default',
                'action' => 'default',
                null => self::useTranslateFilter($domainList, $routerMapping['archive']),
            ]);

        $router->withModule('Default')
            ->addRoute('//<domain>/(international|erasmus)', [
                'presenter' => 'Erasmus',
                'lang' => 'cs',
                null => self::havingDomainLanguage(['cs'], $domainList),
            ], $router::ONE_WAY)
            ->addRoute('//<domain>/<presenter>[/<action>]', [
                'presenter' => 'Default',
                'action' => 'default',
                null => self::useTranslateFilter($domainList, $routerMapping['default']),
            ]);

        return $router;
    }

    public static function createFykosRouter(?array $domainList, array $routerMapping): Router
    {
        $router = new RouteList();

        $router->withModule('Default')
            ->addRoute('//<domain>/<presenter>[/<action>]', [
                'presenter' => 'Default',
                'action' => 'default',
                null => self::useTranslateFilter($domainList, $routerMapping['default']),
            ]);

        return $router;
    }
}
