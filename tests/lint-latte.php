#!/usr/bin/env php
<?php

declare(strict_types=1);

if (function_exists('pcntl_signal')) {
    pcntl_signal(SIGINT, function (): void {
        pcntl_signal(SIGINT, SIG_DFL);
        echo "Terminated\n";
        exit(1);
    });
} elseif (function_exists('sapi_windows_set_ctrl_handler')) {
    sapi_windows_set_ctrl_handler(function () {
        echo "Terminated\n";
        exit(1);
    });
}

set_time_limit(0);



require __DIR__ . '/../app/Bootstrap.php';
require __DIR__ . '/../vendor/autoload.php';

$debug = in_array('--debug', $argv, true);

echo '
    Latte linter
    ------------
    ';


$tempfile = tempnam(sys_get_temp_dir(), "latte-linter");
if (file_exists($tempfile)) {
    unlink($tempfile);
}
mkdir($tempfile);
$engine = App\Bootstrap::bootVyfuk();
$engine->setTempDirectory($tempfile);
$engine = $engine->createContainer()->getByType(Nette\Bridges\ApplicationLatte\LatteFactory::class)->create();

if (class_exists(Nette\Bridges\CacheLatte\CacheMacro::class)) {
    $engine->getCompiler()->addMacro('cache', new Nette\Bridges\CacheLatte\CacheMacro());
}

if (class_exists(Nette\Bridges\ApplicationLatte\UIMacros::class)) {
    Nette\Bridges\ApplicationLatte\UIMacros::install($engine->getCompiler());
}

if (class_exists(Nette\Bridges\FormsLatte\FormMacros::class)) {
    Nette\Bridges\FormsLatte\FormMacros::install($engine->getCompiler());
}

$ok = (new Latte\Tools\Linter($engine, $debug))->scanDirectory(__DIR__ . '/../app/');

$rrmdir = function ($dir, $rrmdir) {
    foreach (glob($dir . '/*') as $file) {
        if (is_dir($file)) {
            $rrmdir($file, $rrmdir);
        } else {
            unlink($file);
        }
    }
    rmdir($dir);
};
$rrmdir($tempfile, $rrmdir);

exit($ok ? 0 : 1);
