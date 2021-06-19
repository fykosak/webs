<?php

namespace App\Modules\Core;

use App\Components\Navigation\Navigation;
use App\Components\Navigation\NavItem;
use Exception;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\Localization\GettextTranslator;
use Fykosak\Utils\Localization\UnsupportedLanguageException;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\Template;

abstract class BasePresenter extends Presenter {

    /** @persistent */
    public ?string $lang = null; // = 'cs';

    protected GettextTranslator $translator;
    protected ServiceEventDetail $serviceEventDetail;

    public function injectServices(GettextTranslator $translator, ServiceEventDetail $serviceEventDetail): void {
        $this->translator = $translator;
        $this->serviceEventDetail = $serviceEventDetail;
    }

    /**
     * @throws UnsupportedLanguageException
     */
    protected function startUp(): void {
        parent::startup();
        $this->localize();
    }

    /**
     * @return Navigation
     * @throws Exception
     */
    /*
    protected function createComponentNavigation(): Navigation {
        $navigation = new Navigation($this->getContext());
        foreach ($this->getNavItems() as $navItem) {
            $navigation->addNavItem($navItem);
        }
        return $navigation;
    }
    */
    /*
    protected function getNavItems(): array {
        return [
            new NavItem(':Default:Default:default', [], _('Archiv'), 'visible-sm-inline glyphicon glyphicon-compressed'),
            new NavItem(':Default:Default:rules', [], _('Pravidla'), 'visible-sm-inline glyphicon glyphicon-exclamation-sign'),
            new NavItem(':Default:Default:faq', [], _('FAQ'), 'visible-sm-inline glyphicon glyphicon-question-sign'),
            new NavItem(':Default:Default:howto', [], _('Návod'), 'visible-sm-inline glyphicon glyphicon-info-sign'),

            //if ($this->yearsService->isRegistrationStarted()) {
            new NavItem(':Default:Default:chat', [], _('Fórum'), 'visible-sm-inline glyphicon glyphicon-comment'),
            new NavItem(':Default:Default:list', [], _('Týmy'), 'visible-sm-inline glyphicon glyphicon-list'),
            //  if ($this->yearsService->isGameStarted()) {
            new NavItem(':Default:Default:default', [], _('Výsledky'), 'visible-sm-inline glyphicon glyphicon-stats'),
            new NavItem(':Default:Default:default', [], _('Nástěnka'), 'visible-sm-inline glyphicon glyphicon-pushpin'),
            //    if ($this->getUser()->isLoggedIn()) {
            new NavItem(':Default:Default:default', [], _('Hra'), 'visible-sm-inline glyphicon glyphicon-tower'),
            new NavItem(':Default:Default:default', [], _('Registrace'), 'visible - sm - inline glyphicon glyphicon-edit'),
        ];
    }
    */

    public function setPageTitle(string $pageTitle): void {
        $this->getTemplate()->pageTitle = $pageTitle;
    }

// ----- PROTECTED METHODS

    protected function createTemplate(): Template {
        $template = parent::createTemplate();
        $template->lang = $this->lang;
        $template->setTranslator($this->translator);

        return $template;
    }

    /* temporary hack for DI */

// -------------- l12n ------------------
    /**
     * @throws UnsupportedLanguageException
     */
    protected function localize(): void {
        $i18nConf = $this->context->parameters['i18n'];
        $this->detectLang($i18nConf);
        $this->translator->setLang($this->lang);
    }

    protected function detectLang($i18nConf): void {
        if (!isset($this->lang)) {
            $this->lang = $this->getHttpRequest()->detectLanguage($this->translator->getSupportedLanguages());
        }
        if (array_search($this->lang, $this->translator->getSupportedLanguages()) === false) {
            $this->lang = $i18nConf['defaultLang'];
        }
    }

    public function getOpenGraphLang(): ?string {
        return $this->getHttpRequest()->getHeader('X-Facebook-Locale');
    }

    protected function changeViewByLang(): void {
        $this->setView($this->getView() . '.' . $this->lang);
    }
}
