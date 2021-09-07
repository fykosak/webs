<?php

declare(strict_types=1);

namespace App\Components\UpperHomeCountdown;

use Fykosak\Utils\BaseComponent\BaseComponent;

class UpperHomeCountdownComponent extends BaseComponent
{
    public function render(): void
    {
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomeCountdown.latte');
    }
}
