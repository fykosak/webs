<?php

declare(strict_types=1);

namespace App\Components\ImageGallery;

use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\DI\Container;
use Nette\Utils\Finder;
use Nette\Utils\Image;

class ImageGalleryControl extends BaseComponent
{
    private string $wwwDir;
    private Cache $cache;

    public function injectStorage(Storage $storage)
    {
        $this->cache = new Cache($storage, 'App\Components\ImageGallery');
    }

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->wwwDir = $container->getParameters()['wwwDir'];
    }

    public static function getImages($path, $wwwDir): array
    {
        $images = [];
        $iterator = null;

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

    /**
     * @throws \Nette\Utils\UnknownImageFileException
     */
    public function render(string $path): void
    {
        $this->template->images = $this->cache->load([$path, $this->wwwDir], fn() => self::getImages($path, $this->wwwDir));
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'default.latte');
    }

    public function renderOneLine(string $path): void
    {
        $this->template->images = $this->cache->load([$path, $this->wwwDir], fn() => self::getImages($path, $this->wwwDir));
        if (count($this->template->images) <= 6) {
            $this->template->previewImages = $this->template->images;
        } else {
            $step = count($this->template->images) / 6;
            $this->template->previewImages = [];
            for ($i = 0; $i < 6; $i++) {
                $this->template->previewImages[] = $this->template->images[(int) ($i * $step)];
            }
        }
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'oneLine.latte');
    }
}
