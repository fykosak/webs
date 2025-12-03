<?php

declare(strict_types=1);

namespace App\Modules\Core;

use App\Components\ImageGallery\ImageGalleryControl;
use App\Components\Navigation\Navigation;
use App\Components\PdfGallery\PdfGalleryControl;
use App\Models\Exceptions\UnderConstructionException;
use App\Models\SettingsService;
use Fykosak\Utils\Localization\GettextTranslator;
use Fykosak\Utils\Localization\UnsupportedLanguageException;
use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\PageTitle;
use InvalidArgumentException;
use Nette\Application\IPresenterFactory;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;
use Nette\DI\Container;

abstract class BasePresenter extends Presenter
{
    /** @persistent */
    public ?string $lang = null; // = 'cs';
    public Language $language;

    public GettextTranslator $translator;
    public SettingsService $settings;
    private IPresenterFactory $presenterFactory;
    private Container $diContainer;

    public function injectServices(
        GettextTranslator $translator,
        SettingsService $settings,
        IPresenterFactory $presenterFactory,
        Container $diContainer
    ): void {
        $this->translator = $translator;
        $this->settings = $settings;
        $this->presenterFactory = $presenterFactory;
        $this->diContainer = $diContainer;
    }

    /**
     * @throws UnsupportedLanguageException
     * @throws \Throwable
     */
    protected function startup(): void
    {
        parent::startup();
        $this->localize();
    }

    public function getContext(): Container
    {
        return $this->diContainer;
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

    /**
     * Helper function get a presenter by it path/name.
     */
    public function getPresenterByName(
        string $presenterName,
        string $action = 'default',
        array $params = []
    ): BasePresenter {
        $targetPresenter = $this->presenterFactory->createPresenter($presenterName);
        if (!$targetPresenter instanceof BasePresenter) {
            throw new InvalidArgumentException('Presenter ' . $presenterName . ' must be an instance of BasePresenter');
        }

        $params = array_merge([
            'lang' => $this->lang // inherit lang from current presenter to keep translator on the same language
        ], $params);

        $targetPresenter->setParent(null, $presenterName);
        $targetPresenter->loadState($params);
        $targetPresenter->changeAction($action);
        $targetPresenter->localize();

        return $targetPresenter;
    }

    /**
     * Determine, if presenter should be visible. Meant to be overwritten by child presenters.
     */
    public function isVisible(): bool
    {
        return true;
    }


    // -------------- i18n ------------------

    /**
     * @throws UnsupportedLanguageException
     */
    protected function localize(): void
    {
        // Lang is null in error presenter because no rote rule was applied
        if (!isset($this->lang) || !$this->lang) {
            // guess language by domain
            $hostname = $this->getHttpRequest()->getUrl()->getHost();
            if (isset($this->settings->domains[$hostname])) {
                $this->lang = $this->settings->domains[$hostname];
            } else {
                // default to en
                $this->lang = 'en';
            }
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
    public function csen(string $cs, string $en): string
    {
        if ($this->translator->lang === Language::cs) {
            return $cs;
        } else {
            return $en;
        }
    }

    public function translateDay(string $day): string
    {
        if ($this->language !== Language::cs) {
            return $day;
        }
        return match ($day) {
            'Monday' => 'Pondělí',
            'Tuesday' => 'Úterý',
            'Wednesday' => 'Středa',
            'Thursday' => 'Čtvrtek',
            'Friday' => 'Pátek',
            'Saturday' => 'Sobota',
            'Sunday' => 'Neděle',
            default => '',
        };
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
