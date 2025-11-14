<?php

declare(strict_types=1);

namespace App\Modules\Fof\ArchiveModule;

use App\Models\Downloader\FKSDBDownloader;
use App\Models\Downloader\ScheduleRequest;
use DateTime;
use Exception;
use Nette\Application\BadRequestException;

class SchedulePresenter extends BasePresenter
{
    private readonly FKSDBDownloader $downloader;

    public function inject(FKSDBDownloader $downloader): void
    {
        $this->downloader = $downloader;
    }

    /**
     * Check that at least one template is available for render
     */
    public function isVisible(): bool
    {
        try {
            $this->findTemplateFile();
            return true;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * @throws BadRequestException
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $scheduleGroups = $this->downloader->download(
            new ScheduleRequest(
                $this->getEvent()->eventId,
                ['weekend', 'info', 'teacher_present']
            )
        );
        usort($scheduleGroups, fn (array $aGroup, array $bGroup): int => $aGroup['start'] <=> $bGroup['start']);

        $scheduleGroupsByDay = [];
        foreach ($scheduleGroups as $group) {
            $day = (new DateTime($group['start']))->format('Y-m-d');
            if (!key_exists($day, $scheduleGroupsByDay)) {
                $scheduleGroupsByDay[$day] = [];
            }
            $scheduleGroupsByDay[$day][] = $group;
        }

        $this->template->scheduleGroupsByDay = $scheduleGroupsByDay;
    }
}
