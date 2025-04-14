<?php

declare(strict_types=1);

namespace App\Components\ImageGallery;

use Fykosak\Utils\Components\DIComponent;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\DI\Container;
use Nette\Utils\Finder;
use Nette\Utils\Image;
use Nette\Utils\UnknownImageFileException;

class ImageGalleryControl extends DIComponent
{
    private readonly string $wwwDir;
    private readonly Cache $cache;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->wwwDir = $container->getParameters()['wwwDir'];
    }

    public function injectStorage(Storage $storage): void
    {
        $this->cache = new Cache($storage, __NAMESPACE__);
    }

    /**
     * @throws UnknownImageFileException
     */
    public static function getImages(string $path, string $wwwDir): array
    {
        $images = [];

        try {
            $iterator = Finder::findFiles('*.jpg', '*.jpeg', '*.JPG', '*.png', '*.gif', '*.bmp', '*.webp')->in($wwwDir . $path)->getIterator();
        } catch (\Exception $e) {
            return [];
        }

        foreach ($iterator as $file) {
            $imageInfo = getimagesize($file->getPathname());
            $wwwPath = substr($file->getPathname(), strlen($wwwDir));
            $images[] = [
                'src' => $wwwPath,
                'width' => $imageInfo[0],
                'height' => $imageInfo[1],
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

    /**
     * @throws \Throwable
     */
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

    /**
     * @throws \Throwable
     */
    public function hasPhotos(string $path): bool
    {
        return count($this->getCachedImages($path)) > 0;
    }

    /**
     * @throws UnknownImageFileException|\Throwable
     */
    public function render(string $path, ?string $layout = null, bool $trimmed = false): void
    {
        $images = $this->getCachedImages($path);
        $this->template->images = $images;

        switch ($layout) {
            case 'randomLine':
                if (!$this->hasPhotos($path)) {
                    return;
                }
                $this->template->previewImages = $this->getPreviewImages($images, (int)(count($images) / 6));
                $template = 'oneLine.latte';
                break;
            case 'orderedLine':
                if (!$this->hasPhotos($path)) {
                    return;
                }
                $this->template->previewImages = $this->getPreviewImages($images, 1);
                $template = 'oneLine.latte';
                break;
            default:
                $template = 'default.latte';
        }

        if ($trimmed) {
            $template = 'trimmedLine.latte';
        }

        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . $template);
    }
}
