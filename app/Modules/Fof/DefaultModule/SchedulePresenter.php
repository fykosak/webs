<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use App\Models\Downloader\Downloaders\FKSDBDownloader;
use App\Models\Downloader\Requests\ScheduleRequest;
use App\Modules\Core\Language;

final class SchedulePresenter extends BasePresenter
{
    private readonly FKSDBDownloader $downloader;

    public function inject(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    public function translateDay(string $day): string
    {
        if ($this->language !== Language::cs) {
            return $day;
        }
        return match ($day) {
            'Monday' => 'Pondělí',
            'Tuesday' => 'Úterý',
            'Wednesday' => 'Středa',
            'Thursday' => 'Čtvrtek',
            'Friday' => 'Pátek',
            'Saturday' => 'Sobota',
            'Sunday' => 'Neděle',
            default => '',
        };
    }

    /**
     * @throws \Throwable
     */
    public function renderDetail(): void
    {
        $groups = $this->downloader->download(
            new ScheduleRequest(
                $this->getNewestEvent()->eventId,
                ['weekend', 'info', 'teacher_present']
            )
        );
        usort($groups, fn(array $aGroup, array $bGroup): int => $aGroup['start'] <=> $bGroup['start']);
        $this->template->groups = $groups;
    }
}
