<?php

declare(strict_types=1);

namespace App\Components\Forms;

use App\Components\Forms\FormComponent;
use Fykosak\Utils\Logging\MessageLevel;
use Nette\DI\Container;
use Nette\Forms\Controls\SubmitButton;
use Nette\Forms\Form;
use App\Components\Forms\FormValidators;
use DateTime;
use App\Models\Downloader\Models\News\NewsColors;
use App\Models\Downloader\Services\NewsService;
use App\Models\Downloader\Models\News\NewsModel;

final class NewsForm extends FormComponent
{
    private NewsService $newsService;

    public function __construct(
        Container $container
    ) {
        parent::__construct($container);
    }

    public function injectNewsService (NewsService $newsService): void
    {
        $this->newsService = $newsService;
    }

    protected function configureForm(Form $form): void
    {
        $titleCs = $form->addText('titleCs', 'Titulek novinky cs', 80);

        $form->addText('titleEn', 'Titulek novinky en')
            ->addConditionOn($form['titleCs'], ~$form::Filled)
            ->setRequired('Musí být vyplněný alespoň jeden titulek');

        $titleCs->addConditionOn($form['titleEn'], ~$form::Filled)
            ->setRequired('Musí být vyplněný alespoň jeden titulek');

        $textCs = $form->addTextArea('textCs', 'Text novinky cs');

        $form->addTextArea('textEn', 'Text novinky en')
            ->addConditionOn($form['textCs'], ~$form::Filled)
            ->setRequired('Musí být vyplněný alespoň jeden text');

        $textCs->addConditionOn($form['textEn'], ~$form::Filled)
            ->setRequired('Musí být vyplněný alespoň jeden text');

        $linkTextCs = $form->addText('linkTextCs', 'Text odkazu cs');

        $linkTextEn = $form->addText('linkTextEn', 'Text odkazu en');

        $form->addText('linkPath', 'Cesta odkazu')
            ->addConditionOn($linkTextCs, $form::Filled)
                ->setRequired('Musí být vyplněná cesta odkazu')
            ->elseCondition()
                ->addConditionOn($linkTextEn, $form::Filled)
                ->setRequired('Musí být vyplněná cesta odkazu');

        $linkTextCs->addConditionOn($form['linkPath'], $form::Filled)
                        ->addConditionOn($form['linkTextEn'], ~$form::Filled)
                        ->setRequired('Musí být vyplněný alespoň jeden text odkazu');

        $linkTextEn->addConditionOn($form['linkPath'], $form::Filled)
                        ->addConditionOn($form['linkTextCs'], ~$form::Filled)
                        ->setRequired('Musí být vyplněný alespoň jeden text odkazu');

        $form->addDateTime('releaseDate', 'Datum zveřejnění')
            ->setRequired('Zadejte datum zveřejnění')
            ->setDefaultvalue(new DateTime);

        $form->addDateTime('displayDate', 'Zobrazené datum');

        $form->addDateTime('endDate', 'Datum konce')
            ->addRule($form::Min, 'Datum konce musí být po datumu zveřejnění.', $form['releaseDate'])
            ->addRule([FormValidators::class, 'validateFutureDate'], 'Datum nesmí být v minulosti.');

        $colors = [];
        foreach (NewsColors::cases() as $case) {
            $colors[$case->value] = $case->label();
        };
        $form->addSelect('color', 'Barva', $colors)
            ->setPrompt('Vyberte jednu z možností');

        $newsId = 0;

        if (in_array($newsId, $this->newsService->getExistingNewsIds())) {

            $news = $this->newsService->getNewsById($newsId);

            $fields = ['titleCs', 'titleEn', 'textCS', 'textEn', 'linkTextCs', 'linkTextEn', 'linkPath', 'releaseDate', 'displayDate', 'endDate', 'color'];

            $values = [$news->title->cs, $news->title->en, $news->text->cs, $news->text->en, $news->linkText->cs, $news->linkText->en, $news->linkPath, $news->releaseDate, $news->displayDate, $news->endDate, $news->color->value];

            $data = array_combine($fields, $values);

            $form->setDefaults($data);
        }
    }

    protected function appendSubmitButton(Form $form): SubmitButton
    {
        return $form->addSubmit('save', 'Uložit')->setHtmlAttribute('class', 'btn btn-primary');
    }

    public function handleSuccess(Form $form): void
    {
        $data = $form->getValues(NewsModel::class);

        bdump($data);

        $this->flashMessage('Novinka uložena', MessageLevel::Success);
        $this->presenter->redirect('this');
    }
}
