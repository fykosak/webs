<?php

declare(strict_types=1);

namespace App\Components\UpperHomeMap;

use Fykosak\Utils\BaseComponent\BaseComponent;


class UpperHomeMapComponent extends BaseComponent
{
    public function render(): void
    {
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'upperHomeMap.latte');
    }
}