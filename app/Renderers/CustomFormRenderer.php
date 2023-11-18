<?php

declare(strict_types=1);

namespace App\Renderers;

use Latte\Engine;
use Nette\Forms\Controls\Button;
use Nette\Forms\Controls\Checkbox;
use Nette\Forms\Form;
use Nette\Forms\IFormRenderer;

class CustomFormRenderer implements IFormRenderer
{
    private Engine $latte;
    private string $lang;

    public function __construct(string $lang)
    {
        $this->latte = new Engine();
        $this->lang = $lang;
    }

    public function render(Form $form): string
    {
        $controls = [
            'buttons' => [],
            'countries' => [],
            'special' => [],
        ];

        foreach ($form->getControls() as $control) {
            if ($control instanceof Button) {
                $controls['buttons'][] = [
                    'control' => $control,
                    'html' => $control->getControl(),
                ];
            } elseif ($control->getName() === 'OneMemberTeams') {
                $controls['special'][] = [
                    'control' => $control,
                    'html' => $control->getControl(),
                ];
            } elseif ($control instanceof Checkbox) {
                $controls['countries'][] = [
                    'control' => $control,
                    'html' => $control->getControlPart()->addAttributes(['class' => 'checkbox-input']),
                ];
            }
        }

        // Pass controls to the template

        return $this->latte->renderToString(__DIR__ . '/templates/formRender.latte', [
            'controls' => $controls,
            'form' => $form,
            'lang' => $this->lang,
        ]);
    }
}
