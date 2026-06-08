<?php

declare(strict_types=1);

namespace App\Models\Images;

use Nette\InvalidStateException;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use Nette\Utils\Image;
use Nette\Utils\ImageColor;
use Nette\Utils\ImageException;

final class ImageManipulator
{
    /**
     * List images in a path. By default it lists the original files,
     * optionally list other variants (that actually exist).
     *
     * @param $path Absolute path to a directory.
     *
     * @phpstan-return \SplFileInfo[]
     */
    public function listImages(string $path, ImageVariant $variant = ImageVariant::Original): array
    {
        $finder = Finder::findFiles('*.jpg', '*.jpeg', '*.png')->in($path);
        $images = [];
        try {
            foreach ($finder as $file) {
                if (ImageVariant::fromFile($file) === $variant) {
                    $images[] = $file;
                }
            }
        } catch (InvalidStateException) {
            return [];
        }

        return $images;
    }

    protected function getFileVariantName(\SplFileInfo $originalFile, ImageVariant $variant): string
    {
        if ($variant === ImageVariant::Original) {
            return $originalFile->getPathname();
        }

        $variantFilename = $originalFile->getBasename('.' . $originalFile->getExtension()) .
            $variant->suffix() . '.' . $originalFile->getExtension();

        return FileSystem::joinPaths($originalFile->getPath(), $variantFilename);
    }

    /**
     * Returns all possible variants as filepaths for existing original images
     * in a path. The variant is NOT guaranteed to exist.
     *
     * @phpstan-return array<array<ImageVariant, string>>
     */
    public function listAllVariants(string $path): array
    {
        $images = [];

        $originalFiles = $this->listImages($path);
        foreach ($originalFiles as $file) {
            $imageVariants = [];
            foreach (ImageVariant::cases() as $variant) {
                $imageVariants[$variant->value] = $this->getFileVariantName($file, $variant);
            }
            $images[] = $imageVariants;
        }

        return $images;
    }

    /**
     * Applies EXIF data, namely rotation and flip.
     * Needed for correct rendering on webpage.
     *
     * Source: https://forum.nette.org/cs/34396-utils-image-auto-orientace-obrazku
     */
    public function applyExifData(Image $image, \SplFileInfo $originalFile): void
    {
        $exif = exif_read_data($originalFile->getRealPath());
        if ($exif === false) {
            return;
        }

        if (!isset($exif['Orientation'])) {
            return;
        }

        $orientation = (int)$exif['Orientation'];

        $backgroundColor = ImageColor::rgb(0, 0, 0);
        if (in_array($orientation, [3, 4])) {
            $image->rotate(180, $backgroundColor);
        } elseif (in_array($orientation, [5, 6])) {
            $image->rotate(-90, $backgroundColor);
        } elseif (in_array($orientation, [7, 8])) {
            $image->rotate(90, $backgroundColor);
        }

        if (in_array($orientation, [2, 5, 7, 4])) {
            $image->flip(IMG_FLIP_HORIZONTAL);
        }
    }

    /**
     * @throws ImageException
     */
    public function resizeAndSave(Image $image, \SplFileInfo $originalFile, ImageVariant $variant): void
    {
        $image->resize(...$variant->size());
        $image->save($this->getFileVariantName($originalFile, $variant));
    }

    public function processDirectory(string $directoryPath): void
    {
        $files = $this->listImages($directoryPath);
        foreach ($files as $file) {
            $image = Image::fromFile($file->getRealPath());

            // EXIF data are not saved with the new image, so the data MUST be
            // applied, otherwise some of the images will be rotated/flipped.
            $this->applyExifData($image, $file);

            $this->resizeAndSave($image, $file, ImageVariant::Full);
            $this->resizeAndSave($image, $file, ImageVariant::Thumb);
        }
    }
}
