<?php

declare(strict_types=1);

namespace App;

use Nette\Application\IPresenterFactory;
use Nette\DI\Container;
use Nette\Http\IRequest;
use Nette\Routing\Router;

class MultiSiteRouter {

    private const SITES = ['Fol'];

    /**
     * @throws UndefinedRouterForSiteException
     */
    private static function siteToRouter(string $site, array $parameters): Router
    {
        switch ($site) {
            case 'Fol': return FolRouter::createRouter($parameters['domains'], $parameters['router-mapping']);
        }

        throw new UndefinedRouterForSiteException();
    }

    /**
     * @throws UnknownDomainException|UndefinedRouterForSiteException
     */
    public static function createRouter(
        IPresenterFactory $presenterFactory,
        IRequest $httpRequest,
        Container $container
    ): Router
    {
        $sites = $container->getParameters()['sites'] ?? [];
        $host = $httpRequest->getUrl()->host;

        $foundSite = null;
        foreach ($sites as $site => $hosts) {
            if (!in_array($site, self::SITES)) {
                continue;
            }

            if (in_array($host, $hosts)) {
                $foundSite = $site;
                break;
            }
        }

        if (!$foundSite) {
            throw new UnknownDomainException("Domain '$host' has no candidate in sites list, therefore it is not possible to say which site should be mapped. Consider adding this domain under 'parameters.sites.[site]' array in your config file.");
        }

        $presenterFactory->setMapping(['*' => ['App\\Modules\\' . $foundSite . 'Site', '*Module', '*Presenter']]);

        return self::siteToRouter($foundSite, $container->getParameters());
    }
}
