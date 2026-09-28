<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use Fykosak\Utils\UI\Navigation\NavItem;
use Fykosak\Utils\UI\Title;
use App\Models\Authentication\Authenticator;
use App\Models\Authentication\UserModel;
use Nette\Application\ForbiddenRequestException;
use App\Models\Downloader\Services\EventService;
use App\Models\Downloader\Services\NewsService;
use App\Components\Forms\NewsForm;
use Nette\DI\Container;
use App\Models\Images\ImageService;
use App\Models\Images\EventImageType;
use Fykosak\Utils\Logging\MessageLevel;
use Fykosak\Utils\Localization\LangMap;

use Nette\Utils\Finder;

class AdminPresenter extends BasePresenter
{
    protected Authenticator $authenticator;
    protected EventService $eventService;
    protected NewsService $newsService;
    protected ImageService $imageService;

    private Container $container;

    public function injectContainer(Container $container): void
    {
        $this->container = $container;
    }

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

        $event = $eventId ? $this->eventService->getEvent($eventId) : $this->eventService->getNewest([10, 11, 12, 18]);
        $this->template->selectedEvent = $event;

        if ($this->imageService->hasPhotosEvent($event)) {
            $media = $this->imageService->getEventImages($event, EventImageType::Default);
            $this->template->media = $this->addMediaNames($media);
        }
    }

    public function addMediaNames(array $media): array
    {
        foreach ($media as $key => $photo) {
            $photo['name'] = str_replace('_full', '', pathinfo($photo['src'])['filename']);

            $media[$key] = $photo;
        }
        return $media;
    }

    public function renderFiles(?int $eventId = null): void
    {
        $this->template->events = array_reverse($this->eventService->getEvents([10, 11, 12, 18]));

        $event = $eventId ? $this->eventService->getEvent($eventId) : $this->eventService->getNewest([10, 11, 12, 18]);
        $this->template->selectedEvent = $event;

        if ($this->hasFilesEvent($event->eventId)) {
            $this->template->files = $this->getFiles($event->eventId);
        }
    }

    private function hasFilesEvent($eventId) {
        return count($this->getFiles($eventId)) > 0;
    }

    public function getFiles($eventId): array
    {
        $mediaDir = $this->getMediaDir();
        $eventDir = $mediaDir . '/download/event/' . $eventId;
        $files = [];

        if (!is_dir($eventDir)) {
            return [];
        }

        try {
            $iterator = Finder::findFiles('*.pdf')->in($eventDir)->getIterator();
        } catch (\Exception $e) {
            return [];
        }

        foreach ($iterator as $file) {
            $name = $file->getBasename('.pdf');
            $path = '/media' . substr($file->getPathname(), strlen($mediaDir));
            $files[] = [
                'path' => $path,
                'name' => $name,
            ];
        };

        usort($files, function ($a, $b) {
            return $a['name'] <=> $b['name'];
        });

        return $files;
    }

    protected function createComponentNewsForm(): NewsForm
    {
        return new NewsForm(
            $this->container,
            (int) $this->getParameter('newsId')
        );
    }

    public function renderNews(?int $newsId = null): void
    {
        $this->template->activeNews = $this->newsService->getActiveNews(4);
        $this->template->newsList = $this->newsService->loadNews();

        $newsItem = !is_null($newsId) ? $this->newsService->getNewsById($newsId) : null;
        $this->template->selectedNewsItem = $newsItem;
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