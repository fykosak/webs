<?php

declare(strict_types=1);

namespace App\Components\PersonSchedule;

use App\Models\Downloader\FKSDBDownloader;
use App\Models\Downloader\ScheduleRequest;
use App\Models\NetteDownloader\ORM\Models\ModelPersonSchedule;
use App\Models\NetteDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\DI\Container;

final class AllScheduleListComponent extends BaseComponent
{
    private ServiceEventDetail $serviceEventDetail;
    private int $eventId;
    private FKSDBDownloader $downloader;

    /** @var ModelPersonSchedule[][] | null */
    private ?array $groupedPersonSchedule = null;

    public function __construct(int $eventId, Container $container)
    {
        $this->eventId = $eventId;
        parent::__construct($container);
    }

    public function injectPrimary(ServiceEventDetail $serviceEventDetail, FKSDBDownloader $downloader): void
    {
        $this->serviceEventDetail = $serviceEventDetail;
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
            'lang' => $this->translator->lang,
            'personGroups' => $this->getGroupedPersonSchedule(),
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
            $personSchedule = $this->serviceEventDetail->getPersonSchedule($this->eventId);
            foreach ($personSchedule as $item) {
                $groups[$item->scheduleItemId] = $groups[$item->scheduleItemId] ?? [];
                $groups[$item->scheduleItemId][] = $item;
            }
            $this->groupedPersonSchedule = $groups;
        }

        return $this->groupedPersonSchedule;
    }
}
