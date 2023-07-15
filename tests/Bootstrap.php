<?php

declare(strict_types=1);

namespace Tests;

use Nette\Configurator;
use Tester\Environment;
use Tracy\Debugger;

// phpcs:disable
@mkdir(__DIR__ . '/../temp/tester');
@mkdir(__DIR__ . '/../temp/tester/log');
const TEMP_DIR = __DIR__ . '/../temp/tester';
require __DIR__ . '/../vendor/autoload.php';

// phpcs:enable

class Bootstrap
{
    public static function boot(string $moduleName): Configurator
    {
        $configurator = new Configurator();

        // Enable Nette Debugger for error visualisation & logging
        $configurator->setDebugMode(false);
        Debugger::$logDirectory = __DIR__ . '/../temp/tester/log';
        Environment::setup();
        error_reporting(~E_USER_DEPRECATED &
            ~E_USER_WARNING & ~E_USER_NOTICE & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED
        );

// Enable RobotLoader - this will load all classes automatically
        $configurator->setTempDirectory(__DIR__ . '/../temp/tester');
        error_reporting(~E_USER_DEPRECATED &
            ~E_USER_WARNING & ~E_USER_NOTICE & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED
        );
        $configurator->createRobotLoader()
            ->addDirectory(__DIR__ . '/../app/')
            ->addDirectory(__DIR__)
            ->register();

// Create Dependency Injection container from config.neon file
        $configurator->addConfig(__DIR__ . "/../app/config/config." . $moduleName . ".neon");
        //$configurator->addConfig(__DIR__ . "/../app/config/config." . $moduleName . ".local.neon");
        $configurator->addConfig(__DIR__ . "/../app/config/config.tester.neon");

        return $configurator;
    }
}

// phpcs:disable
// Configure application
$configurator = Bootstrap::boot(MODULE_NAME);
$container = $configurator->createContainer();

/* Always acquire locks in the order as below! */
define('LOCK_DB', __DIR__ . '/tmp/database.lock');
define('LOCK_UPLOAD', __DIR__ . '/tmp/upload.lock');
return $container;
// phpcs:enable
