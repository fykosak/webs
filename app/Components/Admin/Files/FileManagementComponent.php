<?php

declare(strict_types=1);

namespace App\Components\Admin\Files;

use App\Components\Admin\AbstractFileManagementComponent;
use Nette\DI\Container;
use App\Models\Downloader\Models\EventModel;
use Fykosak\Utils\Logging\MessageLevel;
use Nette\Http\FileUpload;
use Nette\Http\IResponse;
use Nette\Utils\Finder;

final class FileManagementComponent extends AbstractFileManagementComponent
{
    private readonly string $mediaDir;
    private readonly string $wwwDir;

    public function __construct(
        Container $container,
        private readonly EventModel $event
    ) {
        parent::__construct($container, 'admin.files');
        $this->mediaDir = $container->getParameters()['mediaDir'];
        $this->wwwDir = $container->getParameters()['wwwDir'];
    }

    protected function getData(): array
    {
        return ['files' => $this->getFiles()];
    }

    public function handleUpload(): void
    {
        $upload = $this->getHttpRequest()->getFiles()['file'] ?? null;
        $upload = $upload instanceof FileUpload ? $upload : null;

        $name = $upload->getSanitizedName();

        $path = $this->mediaDir . DIRECTORY_SEPARATOR . 'download/event' . DIRECTORY_SEPARATOR . $this->event->eventId;
        if (!is_dir($path) && !mkdir($path)) {
            $this->sendResponse('Složka pro soubory akce neexistuje a nepodařilo se jí vytvořit.', IResponse::S400_BadRequest, MessageLevel::Error);
            return;
        }

        $target = $path . DIRECTORY_SEPARATOR . $name;
        if (is_file($target)) {
            $this->sendResponse(sprintf('Soubor s názvem %s již existuje.', $name), IResponse::S400_BadRequest, MessageLevel::Error);
            return;
        }

        try {
            $upload->move($target);
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

        $oldBasename = $oldName . '.' . $extension;
        $newBasename = $newName . '.' . $extension;
        if (!rename($path . DIRECTORY_SEPARATOR . $oldBasename, $path . DIRECTORY_SEPARATOR . $newBasename)) {
            $error = ['oldBasename' => $oldBasename, 'newBasename' => $newBasename];

            $message = sprintf('Nepodařilo se přejmenovat soubor %s na %s.', $error['oldBasename'], $error['newBasename']);

            $this->sendResponse($message, IResponse::S500_InternalServerError, MessageLevel::Error);
            return;
        }

        $this->sendResponse(sprintf('Soubor %s byl přejmenován na %s.', $oldBasename, $newBasename));
    }

    public function handleDelete(): void
    {
        $name = $this->getRequestValue('name');
        $extension = $this->getRequestValue('extension');
        $path = $this->wwwDir . $this->getRequestValue('path');
        $basename = $name . '.' . $extension;

        if (!unlink($path . DIRECTORY_SEPARATOR . $basename)) {
            $message = sprintf('Nepodařilo se smazat soubor %s.', $basename);

            $this->sendResponse($message, IResponse::S500_InternalServerError, MessageLevel::Error);
            return;
        }

        $this->sendResponse(sprintf('Soubor %s byl smazán.', $basename));
    }

    public function getFiles(): array
    {
        $mediaDir = $this->mediaDir;
        $eventDir = $mediaDir . '/download/event/' . $this->event->eventId;
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
            $pathInfo = pathinfo($file->getPathname());

            $fileName = $pathInfo['filename'];
            $extension = $pathInfo['extension'];
            $path = '/media/download/event/' . $this->event->eventId;

            $files[] = [
                'name' => $fileName,
                'extension' => $extension,
                'path' => $path
            ];
        };

        usort($files, function ($a, $b) {
            return $a['name'] <=> $b['name'];
        });

        return $files;
    }
}