<?php

declare(strict_types=1);

namespace App\Modules\Fof\DefaultModule;

use App\Models\Downloader\FKSDBDownloader;
use App\Models\Downloader\ScheduleRequest;

final class SchedulePresenter extends BasePresenter
{
    private FKSDBDownloader $downloader;

    public function inject(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    public function translateDay(string $day): string
    {
        if ($this->lang !== 'cs') {
            return $day;
        }
        switch ($day) {
            case 'Monday':
                return 'Pondělí';
            case 'Tuesday':
                return 'Úterý';
            case 'Wednesday':
                return 'Středa';
            case 'Thursday':
                return 'Čtvrtek';
            case 'Friday':
                return 'Pátek';
            case 'Saturday':
                return 'Sobota';
            case 'Sunday':
                return 'Neděle';
        }
        return '';
    }

    /**
     * @throws \Throwable
     */
    public function renderDetail(): void
    {
        $groups = $this->downloader->download(new ScheduleRequest(180, ['weekend', 'weekend_info','teacher_present']));
        usort($groups, fn(array $aGroup, array $bGroup): int => $aGroup['start'] <=> $bGroup['start']);
        $this->template->groups = $groups;
    }
}
