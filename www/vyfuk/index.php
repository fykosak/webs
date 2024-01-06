<?php

declare(strict_types=1);

ini_set("soap.wsdl_cache_enabled", '0');

require __DIR__ . '/../../app/Bootstrap.php';
require __DIR__ . '/../../vendor/autoload.php';

App\Bootstrap::bootVyfuk()
    ->createContainer()
    ->getByType(Nette\Application\Application::class)
    ->run();
