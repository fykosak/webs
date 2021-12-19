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

    public function injectStorage(Storage $storage) {
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

        return $images;
    }

    /**
     * @throws \Nette\Utils\UnknownImageFileException
     */
    public function render(string $path): void
    {
        $this->template->images = $this->cache->call([$this, 'getImages'], $path, $this->wwwDir); // Enabled cache
        //$this->template->images = self::getImages($path, $this->wwwDir); // Disabled cache
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'imageGallery.latte');
    }
}
