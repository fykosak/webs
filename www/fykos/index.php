<?php

declare(strict_types=1);

ini_set("soap.wsdl_cache_enabled", '0');

require __DIR__ . '/../../app/Bootstrap.php';

App\Bootstrap::bootFykos()
    ->createContainer()
    ->getByType(Nette\Application\Application::class)
    ->run();
