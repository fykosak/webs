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

    private ?int $newsId;

    public function __construct(
        Container $container,
        ?int $newsId
    ) {
        parent::__construct($container);
        $this->newsId = $newsId;
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

        if (!is_null($this->newsId) && in_array($this->newsId, $this->newsService->getExistingNewsIds())) {

            $news = $this->newsService->getNewsById($this->newsId);

            $fields = ['titleCs', 'titleEn', 'textCs', 'textEn', 'linkTextCs', 'linkTextEn', 'linkPath', 'releaseDate', 'displayDate', 'endDate', 'color'];

            $values = [$news->getTitle()->cs, $news->getTitle()->en, $news->getText()->cs, $news->getText()->en, $news->getLinkText()->cs, $news->getLinkText()->en, $news->linkPath, $news->releaseDate, $news->displayDate, $news->endDate, $news->color->value];

            $data = array_combine($fields, $values);

            $form->setDefaults($data);
        }
    }

    protected function appendSaveButton(Form $form): SubmitButton
    {
        return $form->addSubmit('save', 'Uložit')->setHtmlAttribute('class', 'btn btn-primary');
    }

    protected function appendDeleteButton(Form $form): ?SubmitButton
    {
        return !is_null($this->newsId) ? $form->addSubmit('delete', 'Smazat novinku')->setHtmlAttribute('class', 'btn btn-danger')->setValidationScope([]) : null;
    }

    public function handleSave(Form $form): void
    {
        $newsItem = $form->getValues(NewsModel::class);

        if (is_null($this->newsId)) {
            $newsItem->newsId = max($this->newsService->getExistingNewsIds()) + 1;
            $this->newsService->createNews($newsItem);
            $message = 'Novinka vytvořena';
        } else {
            $newsItem->newsId = $this->newsId;
            $this->newsService->editNews($newsItem);
            $message = 'Novinka upravena';
        }

        $this->getPresenter()->flashMessage($message, MessageLevel::Success);
        $this->presenter->redirect('news');
    }

    public function handleDelete(Form $form): void
    {
        $newsItem = $form->getValues(NewsModel::class);
        $newsItem->newsId = $this->newsId;

        $this->newsService->deleteNews($newsItem);

        $this->getPresenter()->flashMessage('Novinka smazána', MessageLevel::Warning);
        $this->presenter->redirect('this');
    }
}
