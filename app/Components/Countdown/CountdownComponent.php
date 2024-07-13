<?php

declare(strict_types=1);

namespace App\Components\Countdown;

use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

class CountdownComponent extends DIComponent
{
    private readonly string $id;

    public function __construct(
        Container $container,
        private readonly \DateTimeInterface $countdownTo,
        string $id = null
    )
    {
        parent::__construct($container);
        $this->id = $id ?? uniqid();
    }

    public function render(): void
    {

        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'countdown.latte', [
            'id' => $this->id,
            'countdownTo' => $this->countdownTo,
            'lang' => $this->translator->lang,
        ]);
    }
}
