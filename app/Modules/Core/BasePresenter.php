<?php

declare(strict_types=1);

namespace App\Modules\Core;

use App\Components\ImageGallery\ImageGalleryControl;
use App\Components\Navigation\Navigation;
use App\Components\PdfGallery\PdfGalleryControl;
use App\Models\Exceptions\UnderConstructionException;
use Fykosak\Utils\Localization\GettextTranslator;
use Fykosak\Utils\Localization\UnsupportedLanguageException;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;

abstract class BasePresenter extends Presenter
{
    /** @persistent */
    public ?string $lang = null; // = 'cs';
    public Language $language;

    public GettextTranslator $translator;

    public function injectServices(GettextTranslator $translator): void
    {
        $this->translator = $translator;
    }

    /**
     * @throws UnsupportedLanguageException
     * @throws \Throwable
     */
    protected function startUp(): void
    {
        parent::startup();
        $this->localize();
    }

    protected function createComponentNavigation(): Navigation
    {
        $navigation = new Navigation($this->getContext());
        foreach ($this->getNavItems() as $navItem) {
            $navigation->addNavItem($navItem);
        }
        return $navigation;
    }

    /**
     * @return NavItem[]
     */
    abstract protected function getNavItems(): array;

    final public function setPageTitle(PageTitle $pageTitle): void
    {
        $this->template->pageTitle = $pageTitle;
    }

    protected function createTemplate(): Template
    {
        $template = parent::createTemplate();
        $template->lang = $this->lang;
        $template->language = $this->language;
        /** @var \Nette\Bridges\ApplicationLatte\Template $template */
        $template->setTranslator($this->translator);

        return $template;
    }


// -------------- i18n ------------------

    /**
     * @throws UnsupportedLanguageException
     */
    protected function localize(): void
    {
        // Lang is null in error presenter because no rote rule was applied
        if (!isset($this->lang) || !$this->lang) {
            $this->lang = 'en'; // todo guess language by domain
        }
        $this->language = Language::from($this->lang);

        $this->translator->setLang($this->language);
    }

    /**
     * @return string[]
     */
    public function formatTemplateFiles(): array
    {
        [$file,] = parent::formatTemplateFiles();
        return [
            str_replace('.latte', '.' . $this->language->value . '.latte', $file),
            $file,
        ];
    }

    /**
     * Helper function to return correct translation based on the current language
     */
    protected function csen(string $cs, string $en): string
    {
        if ($this->translator->lang === Language::cs) {
            return $cs;
        } else {
            return $en;
        }
    }

    /**
     * @throws UnderConstructionException
     */
    protected function throwUnderConstructionException(): void
    {
        if ($this->getParameter('dev')) {
            return;
        }
        throw new UnderConstructionException();
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentGallery(): ImageGalleryControl
    {
        return new ImageGalleryControl($this->getContext());
    }

    protected function createComponentPdfGallery(): PdfGalleryControl
    {
        return new PdfGalleryControl($this->getContext());
    }
}
