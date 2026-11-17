<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\Title;
use App\Components\Admin\Media\MediaManagementComponent;
use App\Components\Admin\Files\FileManagementComponent;
use App\Models\Authentication\Authenticator;
use App\Models\Authentication\UserModel;
use Nette\Application\ForbiddenRequestException;
use App\Models\Downloader\Services\EventService;
use App\Models\Downloader\Services\NewsService;
use App\Components\Admin\News\NewsManagementForm;
use App\Models\Downloader\Models\EventModel;
use App\Models\Images\ImageService;
use Fykosak\Utils\Logging\MessageLevel;
use Fykosak\Utils\Localization\LangMap;

class AdminPresenter extends BasePresenter
{
    protected Authenticator $authenticator;
    protected EventService $eventService;
    protected NewsService $newsService;
    protected ImageService $imageService;

    public function injectService(
        Authenticator $authenticator,
        EventService $eventService,
        NewsService $newsService,
        ImageService $imageService,
    ): void
    {
        $this->authenticator = $authenticator;
        $this->eventService = $eventService;
        $this->newsService = $newsService;
        $this->imageService = $imageService;
    }

    public function getMediaDir(): string
    {
        $mediaDir = $this->getContext()->getParameters()['mediaDir'];
        return $mediaDir;
    }

    public function checkRequirements($element): void
    {
        parent::checkRequirements($element);

        if (!$this->getUser()->isLoggedIn()) {
            $user = $this->authenticator->authenticateOIDC();
            $this->getUser()->login($user);
        }

        if (!in_array($this->authenticator->requiredGroup, $this->getLoggedUser()->groups)) {
            throw new ForbiddenRequestException();
        }
    }

    public function getLoggedUser(): ?UserModel
    {
        return $this->getUser()->getIdentity();
    }

    public function actionLogout(): void
	{
		$this->getUser()->logout();
		$this->redirect(':Default:Admin:page');
	}

    public function renderMedia(?int $eventId = null): void
    {
        $this->template->events = array_reverse($this->eventService->getEvents([10, 11, 12, 18]));
        $this->template->selectedEvent = $this->getEvent($eventId);
    }

    protected function createComponentMediaManagement(): MediaManagementComponent
    {
        $eventId = $this->getParameter('eventId');
        $event = $this->getEvent((int)$eventId);

        return new MediaManagementComponent($this->getContext(), $event);
    }

    public function getEvent(?int $eventId): EventModel {
        return $eventId ? $this->eventService->getEvent($eventId) : $this->eventService->getNewest([10, 11, 12, 18]);
    }

    public function renderFiles(?int $eventId = null): void
    {
        $this->template->events = array_reverse($this->eventService->getEvents([10, 11, 12, 18]));
        $this->template->selectedEvent = $this->getEvent($eventId);
    }

    protected function createComponentFileManagement(): FileManagementComponent
    {
        $eventId = $this->getParameter('eventId');
        $event = $this->getEvent((int)$eventId);

        return new FileManagementComponent($this->getContext(), $event);
    }

    protected function createComponentNewsManagementForm(): NewsManagementForm
    {
        $newsId = $this->getParameter('newsId');

        if ($newsId !== null) {
            $newsId = (int) $newsId;
        }

        return new NewsManagementForm(
            $this->getContext(),
            $newsId
        );
    }

    public function renderNews(?int $newsId = null): void
    {
        $activeNews = $this->newsService->getActiveNews(4);
        $this->template->activeNews = $activeNews;

        $newsList = $this->newsService->loadNews();
        $this->template->newsList = $this->newsService->sortNews($newsList, $activeNews);

        $newsItem = !is_null($newsId) ? $this->newsService->getNewsById($newsId) : null;
        $this->template->selectedNewsItem = $newsItem;
    }

    public function actionNewsCreate(): void
    {
        $this->setView('newsCreate');
    }

    public function renderNewsCreate(): void
    {
        $this->template->activeNews = $this->newsService->getActiveNews(4);
    }

    /**
     * @return NavItem[]
     */
    protected function getNavItems(): array
    {
        $items = [];

        $items[] = new NavItem(
            new Title(null, 'Správa novinek', 'fa-solid fa-newspaper'),
            ':Default:Admin:news'
        );

        $items[] = new NavItem(
            new Title(null, 'Správa souborů', 'fa-solid fa-file-pen'),
            ':Default:Admin:files'
        );

        $items[] = new NavItem(
            new Title(null, 'Správa fotek', 'fa-solid fa-images'),
            ':Default:Admin:media'
        );

        $items[] = new NavItem(
            new Title(null, sprintf('%s (#%d)', $this->getLoggedUser()->name, $this->getLoggedUser()->id), 'fa-solid fa-user-gear'),
            ':Default:Admin:default'
        );

        $items[] = new NavItem(
            new Title(null, 'Odhlásit se', 'fa-solid fa-arrow-right-from-bracket'),
            ':Default:Admin:logout'
        );

        return $items;
    }

    #[\Override]
    public function flashMessage(
        \Stringable|string|\stdClass|LangMap $message,
        string|MessageLevel $type = 'info'
    ): \stdClass {
        if ($message instanceof LangMap) {
            $message = $this->translator->getVariant($message);
        }
        elseif ($type instanceof MessageLevel) {
            $type = $type->value;
        }
        /** @phpstan-ignore argument.type */
        return parent::flashMessage($message, $type);
    }
}