<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\News;

use App\Models\Downloader\Models\News\NewsColors;
use Fykosak\Utils\Localization\LangMap;

final class NewsModel implements \JsonSerializable
{
    public int $newsId;
    /** @var \Fykosak\Utils\Localization\LangMap $title */
    public LangMap $title;
    /** @var \Fykosak\Utils\Localization\LangMap $text */
    public LangMap $text;
    public ?\DateTimeImmutable $displayDate;
    public ?string $linkPath;
    /** @var \Fykosak\Utils\Localization\LangMap $linkText */
    public ?LangMap $linkText;
    public \DateTimeImmutable $releaseDate;
    public ?\DateTimeImmutable $endDate;
    public ?NewsColors $color;

    public function jsonSerialize(): array
    {
        return [
            'newsId' => $this->newsId,
            'title' => $this->title->toArray(),
            'text' => $this->text->toArray(),
            'displayDate' => $this->displayDate?->format(\DateTimeInterface::ATOM),
            'linkPath' => $this->linkPath,
            'linkText' => $this->linkText?->toArray(),
            'releaseDate' => $this->releaseDate->format(\DateTimeInterface::ATOM),
            'endDate' => $this->endDate?->format(\DateTimeInterface::ATOM),
            'color' => $this->color?->value,
        ];
    }
}
