<?php

declare(strict_types=1);

namespace App\Components\PersonSchedule;

use App\Models\Downloader\EventService;
use App\Models\Downloader\FKSDBDownloader;
use App\Models\Downloader\ScheduleRequest;
use App\Models\NetteDownloader\ORM\Models\ModelPersonSchedule;
use Fykosak\Utils\Components\DIComponent;
use Nette\DI\Container;

final class AllScheduleListComponent extends DIComponent
{
    private readonly EventService $eventService;
    private readonly FKSDBDownloader $downloader;

    /** @var ModelPersonSchedule[][] | null */
    private ?array $groupedPersonSchedule = null;

    public function __construct(private readonly int $eventId, Container $container)
    {
        parent::__construct($container);
    }

    public function injectPrimary(EventService $eventService, FKSDBDownloader $downloader): void
    {
        $this->eventService = $eventService;
        $this->downloader = $downloader;
    }

    /**
     * @throws \Throwable
     */
    public function hasData(?string $groupType): bool
    {
        $data = $this->downloader->download(new ScheduleRequest($this->eventId, $groupType ? [$groupType] : []));
        foreach ($data as $datum) {
            foreach ($datum['items'] as $scheduleItem) {
                if ($scheduleItem['capacity']['used'] > 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @throws \Throwable
     */
    public function render(?string $groupType): void
    {
        $data = $this->downloader->download(new ScheduleRequest($this->eventId, $groupType ? [$groupType] : []));
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'layout.latte', [
            'personGroups' => $this->getGroupedPersonSchedule(),
            'lang' => $this->translator->lang,
            'scheduleGroups' => $data,
        ]);
    }

    /**
     * @return ModelPersonSchedule[][]
     * @throws \Throwable
     */
    private function getGroupedPersonSchedule(): array
    {
        if (is_null($this->groupedPersonSchedule)) {
            $groups = [];
            $personSchedule = $this->eventService->getPersonSchedule($this->eventId);
            foreach ($personSchedule as $item) {
                $groups[$item->scheduleItemId] = $groups[$item->scheduleItemId] ?? [];
                $groups[$item->scheduleItemId][] = $item;
            }
            $this->groupedPersonSchedule = $groups;
        }

        return $this->groupedPersonSchedule;
    }
}
