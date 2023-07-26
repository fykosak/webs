<?php

declare(strict_types=1);

namespace Tests\PresentersTests\PageDisplay;

use Nette\Application\IPresenterFactory;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;
use Tracy\Debugger;

abstract class AbstractPageDisplayTestCase extends TestCase
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    final protected function createRequest(string $presenterName, string $action, array $params): Request
    {
        $params['lang'] = $params['lang'] ?? 'en';
        $params['action'] = $action;
        return new Request($presenterName, 'GET', $params);
    }

    protected function createPresenter(string $presenterName): Presenter
    {
        $_COOKIE['_nss'] = '1';
        $presenterFactory = $this->container->getByType(IPresenterFactory::class);
        /** @var \Nette\Application\UI\Presenter */
        $presenter = $presenterFactory->createPresenter($presenterName);
        $presenter->autoCanonicalize = false;
        return $presenter;
    }

    /**
     * @dataProvider getPages
     */
    final public function testDisplay(string $presenterName, string $action, array $params = []): void
    {
        [$presenterName, $action, $params] = $this->transformParams($presenterName, $action, $params);
        $fixture = $this->createPresenter($presenterName);
        $request = $this->createRequest($presenterName, $action, $params);
        $response = $fixture->run($request);
        /** @var TextResponse $response */
        Assert::type(TextResponse::class, $response);
        $source = $response->getSource();
        Assert::type(Template::class, $source);

        Assert::noError(function () use ($source): string {
            return (string)$source;
        });
    }

    protected function transformParams(string $presenterName, string $action, array $params): array
    {
        return [$presenterName, $action, $params];
    }

    protected static function getPageLangVariants(array $pages): array
    {
        $langs = ['cs', 'en'];
        $langPages = [];
        foreach ($pages as $page) {
            [$presenterName, $action, $params] = $page;
            $params = $params ?? [];
            if (array_key_exists('lang', $params)) {
                $langPages[] = $page;
            } else {
                foreach ($langs as $lang) {
                    $params['lang'] = $lang;
                    $langPages[] = [$presenterName, $action, $params];
                }
            }
        }
        return $langPages;
    }

    abstract public function getPages(): array;
}
