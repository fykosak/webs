<?php

namespace App\Renderers;

use Nette\Forms\IFormRenderer;
use Nette;
use Nette\Forms\Form;
use Nette\Utils\Html;
use Latte\Engine;

class CustomFormRenderer implements IFormRenderer
{
    private $latte;

    public function __construct()
    {
        $this->latte = new Engine();
    }

    public function render(Form $form, $mode = null): string
    {
        $controls = [];
        foreach ($form->getControls() as $control) {
            $controls[] = $control;
        }
        
        // Pass all controls to the template
        $params = ['controls' => $controls];
        
        ob_start();
        $this->latte->render(__DIR__ . '/templates/checkboxRenderer.latte', $params);
        return ob_get_clean();
    }
}
