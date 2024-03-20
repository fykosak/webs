<?php

declare(strict_types=1);

namespace App\Components\Countdown;

use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

class CountdownComponent extends DIComponent
{
    private \DateTimeInterface $countdownTo;
    private string $id;

    public function __construct(Container $container, \DateTimeInterface $countdownTo, string $id = null)
    {
        parent::__construct($container);
        $this->countdownTo = $countdownTo;
        $this->id = $id ?? uniqid();
    }

    public function render(): void
    {
        $this->template->id = $this->id;
        $this->template->countdownTo = $this->countdownTo;
        $this->template->lang = $this->translator->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'countdown.latte');
    }
}
