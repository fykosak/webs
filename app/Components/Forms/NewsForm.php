<?php

declare(strict_types=1);

namespace App\Components\Forms;

use App\Components\Forms\FormComponent;
use Fykosak\Utils\Logging\MessageLevel;
use Nette\DI\Container;
use Nette\Forms\Controls\SubmitButton;
use Nette\Forms\Form;

final class NewsForm extends FormComponent
{
    public function __construct(
        Container $container
    ) {
        parent::__construct($container);
    }

    protected function configureForm(Form $form): void
    {
        $form->addText('title', 'Titulek novinky')
            ->setRequired('Zadejte titulek novinky');

        $form->addTextArea('content', 'Obsah novinky');

        $form->addDateTime('displayDate', 'Zobrazené datum');


    }

    protected function appendSubmitButton(Form $form): SubmitButton
    {
        return $form->addSubmit('save', 'Uložit')->setHtmlAttribute('class', 'btn btn-primary');
    }

    public function handleSuccess(Form $form): void
    {
        $data = $form->getValues();

        bdump($data);

        $this->flashMessage('Novinka uložena', MessageLevel::Success);
        $this->presenter->redirect('this');
    }
}
