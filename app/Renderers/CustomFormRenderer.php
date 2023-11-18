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
    private string $lang;

    public function __construct($lang)
    {
        $this->latte = new Engine();
        $this->lang = $lang;
    }

    public function render(Form $form, $mode = null): string
    {
        $controls = [
            'buttons' => [],
            'countries' => [],
            'special' => []
        ];
        
        foreach ($form->getControls() as $control) {
            if ($control instanceof Nette\Forms\Controls\Button) {
                $controls['buttons'][] = [
                    'control' => $control,
                    'html' => $control->getControl()
                ];
            } elseif ($control->getName() === 'OneMemberTeams') {
                $controls['special'][] = [
                    'control' => $control,
                    'html' => $control->getControl()
                ];
            } elseif ($control instanceof Nette\Forms\Controls\Checkbox) {
                $controls['countries'][] = [
                    'control' => $control,
                    'html' => $control->getControlPart()->addAttributes(['class' => 'checkbox-input'])
                ];
            }
        }
        

        // Pass controls to the template
        $params = ['controls' => $controls];
        $params['lang'] = $this->lang;

        ob_start();
        
        $this->latte->render(__DIR__ . '/templates/formRender.latte', $params);
        return ob_get_clean();
    }

}
