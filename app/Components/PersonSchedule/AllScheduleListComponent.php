<?php

declare(strict_types=1);

namespace App\Components\PersonSchedule;

use App\Models\NetteDownloader\ORM\Models\ModelPersonSchedule;
use App\Models\NetteDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\DI\Container;

class AllScheduleListComponent extends BaseComponent
{
    private ServiceEventDetail $serviceEventDetail;

    private int $eventId;

    /** @var ModelPersonSchedule[][] | null */
    private ?array $groupedPersonSchedule = null;

    public function __construct(int $eventId, Container $container)
    {
        $this->eventId = $eventId;
        parent::__construct($container);
    }

    public function injectPrimary(ServiceEventDetail $serviceEventDetail): void
    {
        $this->serviceEventDetail = $serviceEventDetail;
    }

    public function hasData(?string $groupType): bool
    {
        foreach ($this->serviceEventDetail->getSchedule($this->eventId) as $item) {
            if ($item->scheduleGroupType === $groupType || is_null($groupType)) {
                foreach ($item->scheduleItems as $scheduleItem) {
                    if ($scheduleItem->usedCapacity > 0) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function render(?string $groupType): void
    {
        $scheduleGroups = [];
        foreach ($this->serviceEventDetail->getSchedule($this->eventId) as $item) {
            if ($item->scheduleGroupType === $groupType || is_null($groupType)) {
                $scheduleGroups[] = $item;
            }
        }
        $this->template->lang = $this->getPresenter()->lang;
        $this->template->personGroups = $this->getGroupedPersonSchedule();
        $this->template->scheduleGroups = $scheduleGroups;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'layout.latte');
    }

    /** @return ModelPersonSchedule[][] */
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
