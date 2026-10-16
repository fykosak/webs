<?php

declare(strict_types=1);

namespace App\Components\News;

use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

class NewsComponent extends DIComponent
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function renderVyfuk(array $newsList): void
    {
        $this->template->newsList = $newsList;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'vyfuk.latte');
    }

    public function renderFykos(array $newsList): void
    {
        $this->template->newsList = $newsList;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'fykos.latte', ['lang' => $this->translator->lang]);
    }
}