<?php

declare(strict_types=1);

namespace Tests\Models\Downloader\Downloaders;

use App\Models\Downloader\Downloaders\ProblemManagerDownloader;
use App\Models\Downloader\Requests\ProblemManager\ContestYearRequest;
use App\Models\Downloader\Requests\ProblemManager\SeriesRequest;
use Fykosak\FKSDBDownloaderCore\Requests\Request;
use RuntimeException;

final class MockProblemManagerDownloader extends ProblemManagerDownloader
{
    public function download(Request $request): array
    {

        if ($request instanceof ContestYearRequest) {
            return $this->getContestYears($request->contestId);
        } elseif ($request instanceof SeriesRequest) {
            return $this->getSeries($request->seriesId);
        }

        throw new RuntimeException('Invalid Problem Manager request');
    }

    private function getContestYears(int $contestId): array
    {
        return [[
            'contestYearId' => 1,
            'contestId' => $contestId,
            'year' => 1,
            'series' => [
                [
                    'seriesId' => 1,
                    'contestYearId' => 1,
                    'label' => '1',
                    'release' => null,
                    'deadline' => null
                ]
            ]
        ]];
    }

    private function getSeries(int $seriesId): array
    {
        return [
            'seriesId' => $seriesId,
            'contestYearId' => 1,
            'label' => '1',
            'release' => null,
            'deadline' => null,
            'problems' => [
                [
                    'problemId' => 1,
                    'state' => 'active',
                    'seriesId' => $seriesId,
                    'seriesOrder' => 1,
                    'contestId' => 1,
                    'metadata' => [
                        'name' => [
                            'cs' => 'Šla Nanynka do zelí',
                            'en' => 'Nanynka went to cabbage'
                        ]
                    ],
                    'points' => 10,
                    'texts' => [
                        [
                            'textId' => 1,
                            'problemId' => 1,
                            'lang' => 'cs',
                            'type' => 'task',
                            'html' => 'task'
                        ]
                    ],
                    'topics' => []
                ]
            ],
            'contestYear' => [
                'contestYearId' => 1,
                'contestId' => 1,
                'year' => 1,
            ]
        ];
    }
}
