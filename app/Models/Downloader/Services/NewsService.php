<?php

declare(strict_types=1);

namespace App\Models\Downloader\Services;

use DateTime;
use Nette\DI\Container;
use App\Models\Downloader\Models\News\NewsModel;

final class NewsService extends AbstractJSONService
{
    public string $mediaDir;

    public function __construct(
        Container $container
    ) {
        $this->mediaDir = $container->getParameter('mediaDir');
    }

    private function loadNews(): array
    {
        $json = json_decode(file_get_contents($this->mediaDir . '/news.json'), true)['news'];
        return $this->mapJsonToClass($json, true, NewsModel::class);
    }

    private function saveNews(array $newsList): void
    {
        $json = json_encode($newsList);
        file_put_contents($this->mediaDir . '/news.json', $json);
    }

    public function editNews(NewsModel $newsInput): void
    {
        $newsList = $this->loadNews();

        $updatedNews = [];
        foreach ($newsList as $newsItem) {
            if ($newsItem->newsId !== $newsInput->newsId) {
                $updatedNews[] = $newsItem;
            }
        }
        $updatedNews[] = $newsInput;

        $this->saveNews($updatedNews);
    }

    public function deleteNews(NewsModel $newsInput): void
    {
        $newsList = $this->loadNews();

        $updatedNews = [];
        foreach ($newsList as $newsItem) {
            if ($newsItem->newsId !== $newsInput->newsId) {
                $updatedNews[] = $newsItem;
            }
        }

        $this->saveNews($updatedNews);
    }

    public function createNews(NewsModel $newsInput): void
    {
        $newsList = $this->loadNews();

        $newsList[] = $newsInput;

        $this->saveNews($newsList);
    }

    public function getActiveNews(int $number): array
    {
        $newsList = $this->loadNews();
        usort($newsList, fn(NewsModel $a, NewsModel $b): int => $a->releaseDate <=> $b->releaseDate);

        $activeNews = [];
        $now = new DateTime();
        foreach ($newsList as $newsItem) {
            if (count($activeNews) <= $number && count($activeNews) <= count($newsList)) {
                if ($now > $newsItem->releaseDate and !$newsItem->endDate || $now < $newsItem->endDate) {
                    $activeNews[] = $newsItem;
                }
            }
            break;
        }

        return $activeNews;
    }
}
