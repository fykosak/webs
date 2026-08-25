<?php

declare(strict_types=1);

namespace App\Models\Downloader\Models;

use App\Models\Downloader\Models\News\NewsColors;
use Fykosak\Utils\Localization\LangMap;

final class NewsModel
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
    public ?\DateTime $releaseDate;
    public ?\DateTime $endDate;
    public ?NewsColors $color;
}