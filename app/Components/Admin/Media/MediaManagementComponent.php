<?php

declare(strict_types=1);

namespace App\Components\Admin\Media;

use App\Components\Admin\AbstractFileManagementComponent;
use Nette\DI\Container;
use App\Models\Downloader\Models\EventModel;
use App\Models\Images\ImageService;
use App\Models\Images\EventImageType;
use App\Models\Images\ImageManipulator;
use App\Models\Images\ImageVariant;
use Fykosak\Utils\Logging\MessageLevel;
use Nette\Http\FileUpload;
use Nette\Http\IResponse;

final class MediaManagementComponent extends AbstractFileManagementComponent
{
    protected ImageService $imageService;
    protected ImageManipulator $imageManipulator;
    private readonly string $wwwDir;

    public function __construct(
        Container $container,
        private readonly EventModel $event
    ) {
        parent::__construct($container, 'admin.media');
        $this->wwwDir = $container->getParameters()['wwwDir'];
    }

    public function inject(
        ImageService $imageService,
        ImageManipulator $imageManipulator
    ): void
    {
        $this->imageService = $imageService;
        $this->imageManipulator = $imageManipulator;
    }

    protected function getData(): array
    {
        return ['photos' => $this->getPhotos()];
    }

    public function handleUpload(): void
    {
        $upload = $this->getHttpRequest()->getFiles()['file'] ?? null;
        $upload = $upload instanceof FileUpload ? $upload : null;

        if ($upload === null || !$upload->isOk()) {
            $this->sendResponse('Soubor se nepodařilo nahrát.', IResponse::S400_BadRequest, MessageLevel::Error);
            return;
        }

        $name = $upload->getSanitizedName();

        $path = $this->wwwDir . DIRECTORY_SEPARATOR . 'media/photos/event' . DIRECTORY_SEPARATOR . $this->event->eventId;
        if (!is_dir($path) && !mkdir($path)) {
            $this->sendResponse('Složka pro fotky akce neexistuje a nepodařilo se jí vytvořit.', IResponse::S400_BadRequest, MessageLevel::Error);
            return;
        }

        $target = $path . DIRECTORY_SEPARATOR . $name;
        if (is_file($target)) {
            $this->sendResponse(sprintf('Soubor s názvem %s již existuje.', $name), IResponse::S400_BadRequest, MessageLevel::Error);
            return;
        }

        try {
            $upload->move($target);
            $this->imageManipulator->processDirectory($path);
        } catch (\Throwable) {
            $this->sendResponse(sprintf('Soubor %s se nepodařilo nahrát.', $name), IResponse::S500_InternalServerError, MessageLevel::Error);
            return;
        }

        $this->sendResponse(sprintf('Soubor %s byl nahrán.', $name));
    }

    public function handleRename(): void
    {
        $oldName = $this->getRequestValue('old-name');
        $newName = $this->getRequestValue('new-name');
        $extension = $this->getRequestValue('extension');
        $path = $this->wwwDir . $this->getRequestValue('path');

        if ($oldName === $newName) {
            $this->sendResponse('Nový název souboru nemůže být stejný jako starý.', IResponse::S400_BadRequest, MessageLevel::Error);
            return;
        }

        if (is_file($path . DIRECTORY_SEPARATOR . $newName . '.' . $extension)) {
            $this->sendResponse('Soubor s tímto názvem již existuje.', IResponse::S400_BadRequest, MessageLevel::Error);
            return;
        }

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

    public function sendResponse(string $text, int $responseCode = IResponse::S200_OK, MessageLevel $messageLevel = MessageLevel::Success): void
    {
        // invalidate image cache before sending a response, so that the frontend gallery renders correctly
        $this->imageService->removeEventImagesCache($this->event, EventImageType::Default);

        parent::sendResponse($text, $responseCode, $messageLevel);
    }
}