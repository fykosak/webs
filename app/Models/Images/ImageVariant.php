<?php

declare(strict_types=1);

namespace App\Models\Images;

use Nette\InvalidStateException;

enum ImageVariant: string
{
    case Thumb = 'thumb';
    case Full = 'full';
    case Original = 'original';

    /**
     * @phpstan-return array{int,int}
     */
    public function size(): array
    {
        return match ($this) {
            self::Thumb => [400, 400],
            self::Full => [1920, 1920],
            default => throw new InvalidStateException(),
        };
    }

    public function suffix(): ?string
    {
        return match ($this) {
            self::Thumb => '_thumb',
            self::Full => '_full',
            default => null,
        };
    }

    public static function fromFile(\SplFileInfo $file): self
    {
        $basename = $file->getBasename();
        foreach (self::cases() as $case) {
            // Original does not have a suffix, so we cannot detect it. Only if
            // any other suffix does not match, than it probably is the
            // original file.
            if ($case === self::Original) {
                continue;
            }

            if (str_contains($basename, $case->suffix())) {
                return $case;
            }
        }

        return self::Original;
    }
}
