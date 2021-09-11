<?php

declare(strict_types=1);

namespace App\Components\Countdown;

use Nette\Application\UI\Control;

class CountdownComponent extends Control
{
    private \DateTimeInterface $countdownTo;
    private string $id;

    public function __construct(\DateTimeInterface $countdownTo, string $id = null)
    {
        $this->countdownTo = $countdownTo;
        $this->id = $id ?? uniqid();
    }

    public function render(): void
    {
        $this->template->id = $this->id;
        $this->template->countdownTo = $this->countdownTo;
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'countdown.latte');
    }
}
