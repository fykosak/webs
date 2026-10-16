<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\News;

use App\Models\Downloader\Models\News\NewsColors;
use Fykosak\Utils\Localization\LangMap;

final class NewsModel implements \JsonSerializable
{
    public int $newsId;

    public string $titleCs;
    public string $titleEn;

    public string $textCs;
    public string $textEn;

    public ?\DateTimeImmutable $displayDate;

    public ?string $linkPath;
    public ?string $linkTextCs;
    public ?string $linkTextEn;

    public \DateTimeImmutable $releaseDate;

    public ?\DateTimeImmutable $endDate;

    public ?NewsColors $color;


    public function getTitle(): LangMap
    {
        return new LangMap(
            ['cs' => $this->titleCs, 'en' => $this->titleEn]
        );
    }

    public function getText(): LangMap
    {
        return new LangMap(
            ['cs' => $this->textCs, 'en' => $this->textEn]
        );
    }

    public function getLinkText(): LangMap
    {
        return new LangMap(
            ['cs' => $this->linkTextCs, 'en' => $this->linkTextEn]
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'newsId' => $this->newsId,
            'titleCs' => $this->titleCs,
            'titleEn' => $this->titleEn,
            'textCs' => $this->textCs,
            'textEn' => $this->textEn,
            'displayDate' => $this->displayDate?->format(\DateTimeInterface::ATOM),
            'linkPath' => $this->linkPath,
            'linkTextCs' => $this->linkTextCs,
            'linkTextEn' => $this->linkTextEn,
            'releaseDate' => $this->releaseDate->format(\DateTimeInterface::ATOM),
            'endDate' => $this->endDate?->format(\DateTimeInterface::ATOM),
            'color' => $this->color?->value,
        ];
    }
}
