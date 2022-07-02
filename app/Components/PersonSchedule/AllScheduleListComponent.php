<?php

declare(strict_types=1);

namespace App\Components\PersonSchedule;

use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelPersonSchedule;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\DI\Container;

class AllScheduleListComponent extends BaseComponent
{
    private ServiceEventDetail $serviceEventDetail;

    private int $eventId;
    private ?string $groupType;

    public function __construct(?string $groupType, int $eventId, Container $container)
    {
        $this->groupType = $groupType;
        $this->eventId = $eventId;
        parent::__construct($container);
    }

    public function injectPrimary(ServiceEventDetail $serviceEventDetail): void
    {
        $this->serviceEventDetail = $serviceEventDetail;
    }

    public function render(): void
    {
        $scheduleGroups = [];
        foreach ($this->serviceEventDetail->getSchedule($this->eventId) as $item) {
            if ($item->scheduleGroupType === $this->groupType || is_null($this->groupType)) {
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
        $groups = [];
        $personSchedule = $this->serviceEventDetail->getPersonSchedule($this->eventId);
        foreach ($personSchedule as $item) {
            $groups[$item->scheduleItemId] = $groups[$item->scheduleItemId] ?? [];
            $groups[$item->scheduleItemId][] = $item;
        }
        return $groups;
    }
}
