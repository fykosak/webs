<?php

declare(strict_types=1);

namespace App\Components\AdminMedia;

use App\Models\Downloader\Models\EventModel;
use Nette\DI\Container;
use Fykosak\Utils\FrontendComponent\Components\AjaxComponent;
use App\Models\Images\ImageService;
use App\Models\Images\EventImageType;
use App\Models\Images\ImageVariant;
use Fykosak\Utils\Logging\Message;
use Fykosak\Utils\Logging\MessageLevel;
use Nette\Http\IResponse;

final class AdminMediaComponent extends AjaxComponent
{
    protected ImageService $imageService;
    private readonly string $wwwDir;

    public function __construct(
        Container $container,
        private readonly EventModel $event
    ) {
        parent::__construct($container, 'admin.media');
        $this->wwwDir = $container->getParameters()['wwwDir'];
    }


    protected function configure(): void
    {
        $this->addAction('upload', 'upload!');
        $this->addAction('rename', 'rename!');
        $this->addAction('delete', 'delete!');
    }

    public function injectImageService(
        ImageService $imageService,
    ): void
    {
        $this->imageService = $imageService;
    }

    protected function getData(): array
    {
        return ['photos' => $this->getPhotos()];
    }

    public function handleUpload(): void
    {
        echo("upload");
    }

    public function handleRename(): void
    {
        $oldName = $this->getRequestValue('old-name');
        $newName = $this->getRequestValue('new-name');
        $extension = $this->getRequestValue('extension');
        $path = $this->wwwDir . $this->getRequestValue('path');

        $errors = [];
        foreach (ImageVariant::cases() as $imageVariant) {
            $suffix = $imageVariant->suffix() ?? '';
            $oldBasename = $oldName . $suffix . '.' . $extension;
            $newBasename = $newName . $suffix . '.' . $extension;

            if (!rename($path . DIRECTORY_SEPARATOR . $oldBasename, $path . DIRECTORY_SEPARATOR . $newBasename)) {
                $errors[] = ['oldBasename' => $oldBasename, 'newBasename' => $newBasename];
            }
        }

        if (count($errors) != 0) {
            $message = 'Nepodařilo se přejmenovat';
            switch (count($errors)) {
                case 1:
                    $message .= ' soubor ';
                    break;
                default:
                    $message .= ' soubory ';
            }

            foreach ($errors as $errorList) {
                $message .= sprintf('%s na %s', $errorList['oldBasename'], $errorList['newBasename']);

                if ($errorList === end($errors)) {
                    $message .= '.';
                    break;
                }

                $message .= ', ';
            }

            $this->sendResponse($message, IResponse::S500_InternalServerError, MessageLevel::Error);
            return;
        }

        $this->imageService->removeEventImagesCache($this->event, EventImageType::Default);
        $this->sendResponse(sprintf('Soubor %s byl přejmenován na %s.', $oldName . '.' . $extension, $newName . '.' . $extension));
    }

    public function handleDelete(): void
    {
        $name = $this->getRequestValue('name');
        $extension = $this->getRequestValue('extension');
        $path = $this->wwwDir . $this->getRequestValue('path');

        $errors = [];
        foreach (ImageVariant::cases() as $imageVariant) {
            $suffix = $imageVariant->suffix() ?? '';
            $basename = $name . $suffix . '.' . $extension;

            if (!unlink($path . DIRECTORY_SEPARATOR . $basename)) {
                $errors[] = $basename;
            }
        }

        if (count($errors) != 0) {
            $message = 'Nepodařilo se smazat';
            switch (count($errors)) {
                case 1:
                    $message .= ' soubor ';
                    break;
                default:
                    $message .= ' soubory ';
            }

            foreach ($errors as $error) {
                $message .= $error;

                if ($error === end($errors)) {
                    $message .= '.';
                    break;
                }

                $message .= ', ';
            }

            $this->sendResponse($message, IResponse::S500_InternalServerError, MessageLevel::Error);
            return;
        }

        $this->imageService->removeEventImagesCache($this->event, EventImageType::Default);
        $this->sendResponse(sprintf('Soubor %s byl smazán.', $name . '.' . $extension));
    }

    private function getPhotos(): array
    {
        $photos = [];
        if ($this->imageService->hasPhotosEvent($this->event)) {
            $photoList = $this->imageService->getEventImages($this->event, EventImageType::Default);
            $photoList = $this->addMediaNames($photoList);
            foreach ($photoList as $photo) {
                $filteredData = [];
                foreach ($photo as $dataKey => $dataValue) {
                    switch ($dataKey) {
                        case 'name':
                        case 'extension':
                        case 'path':
                        case 'thumbSrc':
                            $filteredData[$dataKey] = $dataValue;
                            break;
                        default:
                            break;
                    }
                }
                $photos[] = $filteredData;
            }
        }

        return $photos;
    }

    private function addMediaNames(array $media): array
    {
        foreach ($media as $key => $photo) {
            $pathInfo = pathinfo($photo['src']);

            $fileName = str_replace(ImageVariant::Full->suffix(), '', $pathInfo['filename']);
            $extension = $pathInfo['extension'];
            $path = $pathInfo['dirname'];

            $photo['name'] = $fileName;
            $photo['extension'] = $extension;
            $photo['path'] = $path;

            $media[$key] = $photo;
        }

        return $media;
    }

    private function getRequestValue(string $key): ?string
    {
        $value = $this->getHttpRequest()->getPost($key);
        return is_string($value) ? $value : null;
    }

    private function sendResponse(string $text, int $responseCode = IResponse::S200_OK, MessageLevel $messageLevel = MessageLevel::Success): void {
        $this->getLogger()->log(new Message($text, $messageLevel));
        $this->sendAjaxResponse($responseCode);
    }

}