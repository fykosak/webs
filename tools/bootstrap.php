<?php

declare(strict_types=1);

namespace FKSDB\Tools;

use App\Bootstrap;
use Nette\Http\Request;
use Nette\Http\UrlScript;

require __DIR__ . '/../vendor/autoload.php';
// Load Nette Framework
require __DIR__ . '/../app/Bootstrap.php';

// Configure application
$configurator = Bootstrap::bootFykos();

$container = $configurator->createContainer();

return $configurator->createContainer();
