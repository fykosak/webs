<?php

ini_set("soap.wsdl_cache_enabled", 0);

require __DIR__ . '/../../app/Bootstrap.php';

App\Bootstrap::bootDsef()
    ->createContainer()
    ->getByType(Nette\Application\Application::class)
    ->run();
