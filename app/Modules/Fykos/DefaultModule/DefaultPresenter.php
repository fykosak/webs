<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

class DefaultPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->template->events = [
            'Naboj' => [
                'heading' => 'Fyzikální Náboj',
                'date' => date('Y-m-d', strtotime('2023-11-03'))
            ],
            'DSEF' => [
                'heading' => 'DSEF',
                'date' => date('Y-m-d', strtotime('2023-11-06'))
            ],
            'FOL' => [
                'heading' => 'FOL',
                'date' => date('Y-m-d', strtotime('2023-11-21'))
            ],
            'FOF' => [
                'heading' => 'Fyziklání',
                'date' => date('Y-m-d', strtotime('2024-02-16'))
            ],
            'serie-1' => [
                'heading' => 'Deadline 1. série',
                'date' => date('Y-m-d', strtotime('2023-10-10'))
            ],
            'serie-2' => [
                'heading' => 'Deadline 2. série',
                'date' => date('Y-m-d', strtotime('2023-11-21'))
            ],
            'serie-3' => [
                'heading' => 'Deadline 3. série',
                'date' => date('Y-m-d', strtotime('2024-01-02'))
            ],
            'serie-4' => [
                'heading' => 'Deadline 4. série',
                'date' => date('Y-m-d', strtotime('2024-02-27'))
            ],
            'serie-5' => [
                'heading' => 'Deadline 5. série',
                'date' => date('Y-m-d', strtotime('2024-04-09'))
            ],
            'serie-6' => [
                'heading' => 'Deadline 6. série',
                'date' => date('Y-m-d', strtotime('2024-05-14'))
            ]
        ];
        $this->template->timelineBegin = "1.&nbsp;10. 2023";
        $this->template->timelineEnd = "30.&nbsp;5. 2024";

        // save the index
        // foreach ($this->template->events as $key => $event) {
        //     $event['name'] = $key;
        //     $this->template->events[$key] = $event;
        // }

        // Sort events by date
        usort($this->template->events, function ($a, $b) {
            $dateA = strtotime($a['date']);
            $dateB = strtotime($b['date']);
            return $dateA - $dateB;
        });


        // Find the event with the closest date larger than the current date
        $currentDate = strtotime(date('Y-m-d'));
        $closestIndex = 0;

        foreach ($this->template->events as $event) {
            $eventDate = strtotime($event['date']);

            if ($eventDate < $currentDate) {
                $closestIndex += 1;
            } else {
                break;
            }
        }

        $this->template->closestEventIndex = $closestIndex;
        $this->template->numOfEvents = count($this->template->events);
    }
}
