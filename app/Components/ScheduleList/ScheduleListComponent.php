<?php

declare(strict_types=1);

namespace App\Components\ScheduleList;

use App\Models\Downloader\Downloaders\FKSDBDownloader;
use App\Models\Downloader\Models\EventModel;
use App\Models\Downloader\Requests\ScheduleRequest;
use DateTime;
use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

class ScheduleListComponent extends DIComponent
{
    private readonly FKSDBDownloader $fksdbDownloader;

    public function __construct(Container $container, private readonly EventModel $event)
    {
        parent::__construct($container);
    }

    public function inject(fksdbDownloader $fksdbDownloader): void
    {
        $this->fksdbDownloader = $fksdbDownloader;
    }

    /**
     * @param int[] $scheduleItemIdsToSkip Ids of Schedule items, that should not be rendered
     * @param int $competitionDetailItemId Schedule item id for competition to render timeline
     */
    public function render(array $scheduleItemIdsToSkip, int $competitionDetailItemId)
    {
        $scheduleGroups = $this->fksdbDownloader->download(
            new ScheduleRequest(
                $this->event->eventId,
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
        $this->template->scheduleItemIdsToSkip = $scheduleItemIdsToSkip;
        $this->template->competitionDetailItemId = $competitionDetailItemId;
        $this->template->language = $this->translator->lang;

        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'scheduleList.latte');
    }
}
