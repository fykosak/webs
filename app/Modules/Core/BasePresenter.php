<?php

declare(strict_types=1);

namespace App\Modules\Core;

use App\Components\Navigation\Navigation;
use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelEvent;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\Localization\GettextTranslator;
use Fykosak\Utils\Localization\UnsupportedLanguageException;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;

abstract class BasePresenter extends Presenter
{

    /** @persistent */
    public ?string $lang = null; // = 'cs';

    public GettextTranslator $translator;
    protected ServiceEventDetail $serviceEventDetail;

    public function injectServices(GettextTranslator $translator, ServiceEventDetail $serviceEventDetail): void
    {
        $this->translator = $translator;
        $this->serviceEventDetail = $serviceEventDetail;
    }

    /**
     * @throws UnsupportedLanguageException
     */
    protected function startUp(): void
    {
        parent::startup();
        $this->localize();
    }

    /**
     * @return Navigation
     * @throws \Exception
     */
    protected function createComponentNavigation(): Navigation
    {
        $navigation = new Navigation($this->getContext());
        foreach ($this->getNavItems() as $navItem) {
            $navigation->addNavItem($navItem);
        }
        return $navigation;
    }

    abstract protected function getNavItems(): array;

    public function setPageTitle(string $pageTitle): void
    {
        $this->getTemplate()->pageTitle = $pageTitle;
    }

    protected function createTemplate(): Template
    {
        $template = parent::createTemplate();
        $template->lang = $this->lang;
        $template->setTranslator($this->translator);

        return $template;
    }


// -------------- i18n ------------------

    /**
     * @throws UnsupportedLanguageException
     */
    protected function localize(): void
    {
        $i18nConf = $this->context->parameters['i18n'];
        $this->detectLang($i18nConf);
        $this->translator->setLang($this->lang);
    }

    protected function detectLang(array $i18nConf): void
    {
        if (!isset($this->lang)) {
            $this->lang = $this->getHttpRequest()->detectLanguage($this->translator->getSupportedLanguages());
        }
        if (array_search($this->lang, $this->translator->getSupportedLanguages()) === false) {
            $this->lang = $i18nConf['defaultLang'];
        }
    }

    protected function changeViewByLang(): void
    {
        $this->setView($this->getView() . '.' . $this->lang);
    }

    public static function createEventKey(ModelEvent $event): string
    {
        $year = $event->begin->format('Y');
        $month = $event->begin->format('m');
        $monthName = strtolower($event->begin->format('M'));
        return $month < 10 ? ($year . '-' . $monthName) : $year;
    }
}
