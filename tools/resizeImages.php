<?php

declare(strict_types=1);

namespace Tools;

use App\Models\Images\ImageManipulator;
use Nette\DI\Container;
use Nette\Utils\FileSystem;

if ($argc < 2) {
    print_r("Missing target directory containing images\n");
    print_r("Usage: php resizeImages.php <path>\n");
    exit(1);
}

/** @var Container $container */
$container = require __DIR__ . '/bootstrap.php';

/** @var ImageManipulator $imageManipulator */
$imageManipulator = $container->getByType(ImageManipulator::class);

$path = FileSystem::resolvePath(getcwd(), $argv[1]);

echo("Processing directory $path\n");
$imageManipulator->processDirectory($path);
echo("Processing done\n");
