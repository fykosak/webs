<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models\News;

use App\Models\Downloader\Models\News\NewsColors;
use Fykosak\Utils\Localization\LangMap;

final class NewsModel implements \JsonSerializable
{
    public int $newsId;
    /** @var LangMap $title */
    public LangMap $title;
    /** @var LangMap $text */
    public LangMap $text;
    public \DateTime $displayDate;
    public ?string $linkPath;
    /** @var LangMap $linkText */
    public ?LangMap $linkText;
    public \DateTime $releaseDate;
    public ?\DateTime $endDate;
    public ?NewsColors $color;

    public function jsonSerialize(): array
    {
        return [
            'newsId' => $this->newsId,
            'title' => $this->title->__serialize(),
            'text' => $this->text->__serialize(),
            'displayDate' => $this->displayDate->format(\DateTimeInterface::ATOM),
            'linkPath' => $this->linkPath,
            'linkText' => $this->linkText?->__serialize(),
            'releaseDate' => $this->releaseDate->format(\DateTimeInterface::ATOM),
            'endDate' => $this->endDate?->format(\DateTimeInterface::ATOM),
            'color' => $this->color?->value,
        ];
    }
}