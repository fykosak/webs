<?php

declare(strict_types=1);

namespace App\Components\ImageGallery;

use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\DI\Container;
use Nette\Utils\Finder;
use Nette\Utils\Image;
use Nette\Utils\UnknownImageFileException;

class ImageGalleryControl extends BaseComponent
{
    private string $wwwDir;
    private Cache $cache;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->wwwDir = $container->getParameters()['wwwDir'];
    }

    public function injectStorage(Storage $storage): void
    {
        $this->cache = new Cache($storage, __NAMESPACE__);
    }

    public static function getImages($path, $wwwDir): array
    {
        $images = [];
        try {
            $iterator = Finder::findFiles('*.jpg')->in($wwwDir . $path)->getIterator();
        } catch (\Exception $e) {
            return [];
        }

        foreach ($iterator as $file) {
            $image = Image::fromFile($file->getPathname());
            $wwwPath = substr($file->getPathname(), strlen($wwwDir));
            $images[] = [
                'src' => $wwwPath,
                'width' => $image->getWidth(),
                'height' => $image->getHeight(),
            ];
        }

        usort($images, function ($a, $b) {
            return $a['src'] <=> $b['src'];
        });

        foreach ($images as $index => &$image) {
            $image['index'] = $index;
        }

        return $images;
    }

    private function getCachedImages(string $path): array
    {
        return $this->cache->load(
            [$path, $this->wwwDir],
            fn() => self::getImages($path, $this->wwwDir)
        );
    }

    private function getPreviewImages(array $images, int $step): array
    {
        if (count($images) <= 6) {
            return $images;
        }

        $previewImages = [];
        for ($i = 0; $i < 6; $i++) {
            $previewImages[] = $images[(int)($i * $step)];
        }

        return $previewImages;
    }

    public function hasPhotos(string $path): bool
    {
        return count($this->getCachedImages($path)) > 0;
    }

    /**
     * @throws UnknownImageFileException|\Throwable
     */
    public function render(string $path): void
    {
        $this->template->images = $this->getCachedImages($path);
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'default.latte');
    }

    public function renderRandomLine(string $path): void
    {
        if (!$this->hasPhotos($path)) {
            return;
        }

        $images = $this->getCachedImages($path);
        $this->template->images = $images;
        $this->template->previewImages = $this->getPreviewImages($images, (int)(count($images) / 6));
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'oneLine.latte');
    }

    public function renderOrderedLine(string $path): void
    {
        if (!$this->hasPhotos($path)) {
            return;
        }

        $images = $this->getCachedImages($path);
        $this->template->images = $images;
        $this->template->previewImages = $this->getPreviewImages($images, 1);
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'oneLine.latte');
    }
}
