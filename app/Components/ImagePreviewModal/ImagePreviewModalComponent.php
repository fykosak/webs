<?php

declare(strict_types=1);

namespace App\Components\ImagePreviewModal;

use Fykosak\Utils\Components\DIComponent;

class ImagePreviewModalComponent extends DIComponent
{
    public function render()
    {
        $this->template->language = $this->translator->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'imagePreviewModal.latte');
    }
}
