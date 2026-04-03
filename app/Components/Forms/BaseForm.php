<?php

declare(strict_types=1);

namespace App\Components\Forms;

use Nette\Application\UI\Form;

class BaseForm extends Form
{
    /**
     * File upload form
     */

    protected function createComponentFileUploadForm(): Form
    {
        $form = new Form();
        $form->addMultiUpload('file', 'Nahrát soubor:');
        $form->addSubmit('submit', 'Nahrát');
        /*$form->onSuccess[] = [$this, 'formSucceded'];*/
        $form->onRender[] = fn(Form $form) => $this->styleForm($form);
        return $form;
    }

     /**
     * Style form using bootstrap
     * Code from: https://github.com/nette/forms/blob/master/examples/bootstrap5-rendering.php
     */
     final protected function styleForm(Form $form): void
    {
        /** @var DefaultFormRenderer $renderer */
        $renderer = $form->getRenderer();
        $renderer->wrappers['controls']['container'] = null;
        $renderer->wrappers['pair']['container'] = 'div class="mb-3 row"';
        $renderer->wrappers['label']['container'] = 'div class="col-sm-3 col-form-label"';
        $renderer->wrappers['control']['container'] = 'div class=col-sm-9';
        $renderer->wrappers['control']['description'] = 'span class=form-text';
        $renderer->wrappers['control']['errorcontainer'] = 'span class=invalid-feedback';
        $renderer->wrappers['control']['.error'] = 'is-invalid';
        $renderer->wrappers['error']['container'] = 'div class="alert alert-danger"';

        foreach ($form->getControls() as $control) {
            $type = $control->getOption('type');
            if ($type === 'button') {
                $control->getControlPrototype()->addClass(
                    empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-secondary'
                );
                $usedPrimary = true;
            } elseif (in_array($type, ['text', 'textarea', 'select', 'datetime', 'file'], true)) {
                $control->getControlPrototype()->addClass('form-control');
            } elseif (in_array($type, ['checkbox', 'radio'], true)) {
                $control->getControlPrototype()->addClass('form-check-input');
                $control->getContainerPrototype()->setName('div')->addClass('form-check');
            } elseif ($type === 'color') {
                $control->getControlPrototype()->addClass('form-control form-control-color');
            }
        }
    }
}