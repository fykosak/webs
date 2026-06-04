<?php

declare(strict_types=1);

namespace App\Components\ImageGallery;

use App\Models\Downloader\Models\EventModel;
use App\Models\Images\ImageService;
use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;
use Nette\Utils\UnknownImageFileException;

/**
 * @phpstan-import-type ImageInfo from ImageService
 */
class ImageGalleryControl extends DIComponent
{
    private readonly ImageService $imageService;

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function inject(ImageService $imageService): void
    {
        $this->imageService = $imageService;
    }

    /**
     * @param ImageInfo[] $images
     * @return ImageInfo[] $images
     */
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
     * @param ImageInfo[] $images
     */
    private function renderTemplate(array $images, ?string $layout, bool $trimmed): void
    {
        $template = 'default.latte';

        switch ($layout) {
            case 'randomLine':
                $this->template->previewImages = $this->getPreviewImages($images, (int)(count($images) / 6));
                $template = 'oneLine.latte';
                break;
            case 'orderedLine':
                $this->template->previewImages = $this->getPreviewImages($images, 1);
                $template = 'oneLine.latte';
                break;
        }

        if ($trimmed) {
            $template = 'trimmedLine.latte';
        }

        $this->template->images = $images;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . $template);
    }

    /**
     * @throws UnknownImageFileException|\Throwable
     */
    public function render(EventModel $event, ?string $layout = null, bool $trimmed = false): void
    {
        if (!$this->imageService->hasPhotosEvent($event)) {
            return;
        }
        $images = $this->imageService->getEventImages($event);
        $this->renderTemplate($images, $layout, $trimmed);
    }

    public function renderPath(string $path, ?string $layout = null, bool $trimmed = false): void
    {
        if (!$this->imageService->hasPhotosPath($path)) {
            return;
        }
        $images = $this->imageService->getPathImages($path);
        $this->renderTemplate($images, $layout, $trimmed);
    }
}
