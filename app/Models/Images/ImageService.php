<?php

declare(strict_types=1);

namespace App\Models\Images;

use App\Models\Downloader\Models\EventModel;
use DateTime;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\DI\Container;
use Nette\InvalidStateException;
use Nette\NotImplementedException;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;

/**
 * @phpstan-type ImageInfo array{
 *    src: string,
 *    width: non-negative-int,
 *    height: non-negative-int,
 *    index: non-negative-int
 * }
 */
final class ImageService
{
    private readonly string $wwwDir;
    private readonly Cache $cache;

    public function __construct(Container $container, Storage $storage)
    {
        $this->wwwDir = $container->getParameter('wwwDir');
        $this->cache = new Cache($storage, self::class);
    }

    private function getEventImageDirectory(EventModel $event): string
    {
        $eventDirectory = match ($event->eventTypeId) {
            // FOF
            1 => sprintf('%d', $event->getYear()),
            // DSEF
            2, 14 => sprintf('%d-%d', $event->getYear(), $event->getMonth()),
            // Camp spring
            4 => sprintf('events/sous-jaro/rocnik%d/carousel-photos', $event->year),
            5 => sprintf('events/sous-podzim/rocnik%d/carousel-photos', $event->year),
            // FOL
            9 => sprintf('%d', $event->getYear()),
            // Tábor, Jarní setkání, Podzimní setkání, Víkendovka
            10, 11, 12, 18 => sprintf('event/%d', $event->eventId),
            // Internships
            19 => sprintf('events/interships/'),
            default => throw new NotImplementedException(
                sprintf('Images for event type %d not implemented', $event->eventTypeId)
            )
        };

        return FileSystem::joinPaths('media/photos', $eventDirectory);
    }

    /**
     * Lists images in a given directory.
     *
     * @param string $path Path to the directory relative to WWW.
     * @phpstan-return ImageInfo[]
     */
    private function listImagesInDirectory(string $path): array
    {
        $images = [];

        try {
            $iterator = Finder::findFiles('*.jpg', '*.jpeg', '*.JPG', '*.png', '*.gif', '*.bmp', '*.webp')->
                in(FileSystem::joinPaths($this->wwwDir, $path))->getIterator();

            foreach ($iterator as $file) {
                $imageInfo = getimagesize($file->getPathname());
                $wwwPath = substr($file->getPathname(), strlen($this->wwwDir));
                $images[] = [
                    'src' => $wwwPath,
                    'width' => $imageInfo[0],
                    'height' => $imageInfo[1],
                    'index' => 0
                ];
            }
        } catch (InvalidStateException $e) {
            return [];
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
     * @param string $path Path to the directory relative to WWW.
     * @return ImageInfo[]
     */
    public function getPathImages(string $path): array
    {
        return $this->cache->load(
            [$path],
            function (&$dependencies) use ($path) {
                $dependencies[Cache::Expire] = '20 minutes';
                return $this->listImagesInDirectory($path);
            }
        );
    }

    /**
     * Cached function to get list of images for event.
     * Paths relative to WWW directory.
     *
     * @return ImageInfo[]
     */
    public function getEventImages(EventModel $event, EventImageType $imageType): array
    {
        $imageDirectory = FileSystem::joinPaths($this->getEventImageDirectory($event), $imageType->value);

        return $this->cache->load(
            [$imageDirectory],
            function (&$dependencies) use ($imageDirectory, $event) {
                $dependencies[Cache::Expire] = '20 minutes';
                $timeDiff = $event->getEventPeriod()->begin->diff(new DateTime());
                // Older than a year
                if ($timeDiff->y > 1) {
                    $dependencies[Cache::Expire] = '1 year';
                }

                return $this->listImagesInDirectory($imageDirectory);
            }
        );
    }

    public function hasPhotosPath(string $path): bool
    {
        return count($this->getPathImages($path)) > 0;
    }

    public function hasPhotosEvent(EventModel $event, EventImageType $imageType = EventImageType::Default): bool
    {
        return count($this->getEventImages($event, $imageType)) > 0;
    }

    /**
     * @phpstan-return ?ImageInfo
     */
    public function getRandomImage(EventModel $event): ?array
    {
        if (!$this->hasPhotosEvent($event, EventImageType::Default)) {
            return null;
        }

        $photos = $this->getEventImages($event, EventImageType::Default);
        return $photos[array_rand($photos)];
    }
}
