<?php

declare(strict_types=1);

namespace App\Components\Forms;

use Fykosak\Utils\Components\DIComponent;
use Fykosak\Utils\FormControl\FormControl;
use Fykosak\Utils\Logging\MessageLevel;
use Nette\Application\AbortException;
use Nette\Forms\Controls\SubmitButton;
use Nette\Forms\Form;
use Tracy\Debugger;

/**
 * @phpstan-extends DIComponent<AppLang>
 */
abstract class FormComponent extends DIComponent
{
    public function render(): void
    {
        $this->template->render($this->getTemplatePath());
    }

    protected function getTemplatePath(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'form.latte';
    }

    final protected function createComponentFormControl(): FormControl
    {
        $control = new FormControl($this->container);
        $this->addComponent($control, 'formControl');

        $this->configureForm($control->getForm());

        $this->appendSubmitButton($control->getForm())->onClick[] =
            function (SubmitButton $button): void {
                try {
                    $this->handleSuccess($button->getForm());
                } catch (AbortException $exception) {
                    throw $exception;
                } catch (\Throwable $exception) {
                    Debugger::log($exception, Debugger::EXCEPTION);
                    Debugger::barDump($exception);
                    $this->flashMessage($exception->getMessage(), MessageLevel::Error);
                }
            };

        return $control;
    }

    abstract protected function configureForm(Form $form): void;

    abstract protected function appendSubmitButton(Form $form): SubmitButton;

    abstract protected function handleSuccess(Form $form): void;
}