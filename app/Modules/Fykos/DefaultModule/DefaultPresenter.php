<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

class DefaultPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->template->events = [  
            'Naboj' => [
                'name' => 'Fyzikální Náboj',
                'date' => strtotime('2023-11-03')
            ],
            'DSEF' => [
                'name' => 'DSEF',
                'date' => strtotime('2023-11-06')
            ],
            'FOL' => [
                'name' => 'FOL',
                'date' => strtotime('2023-11-21')
            ],
            'FOF' => [
                'name' => 'Fyziklání',
                'date' => strtotime('2024-02-16')
            ],
            'serie-1' => [
                'name' => 'Deadline 1. série',
                'date' => strtotime('2023-10-10')
            ],
            'serie-2' => [
                'name' => 'Deadline 2. série',
                'date' => strtotime('2023-11-21')
            ],
            'serie-3' => [
                'name' => 'Deadline 3. série',
                'date' => strtotime('2024-01-02')
            ],
            'serie-4' => [
                'name' => 'Deadline 4. série',
                'date' => strtotime('2024-02-27')
            ],
            'serie-5' => [
                'name' => 'Deadline 5. série',
                'date' => strtotime('2024-04-09')
            ],
            'serie-6' => [
                'name' => 'Deadline 6. série',
                'date' => strtotime('2024-05-14')
            ],
        ];
          
        // Sort events by date
        usort($this->template->events, function($a, $b) {
            $dateA = $a['date'];
            $dateB = $b['date'];
            return $dateA - $dateB;
        });

        // Find the event with the closest date larger than the current date
        $currentDate = strtotime(date('d-m-Y'));
        $closestIndex = 0;

        foreach ($this->template->events as $event) {
            $eventDate = $event['date'];
            
            if ($eventDate < $currentDate) {
                $closestIndex += 1;
            }
            else {
                break;
            }
        }

        $this->template->closestEventIndex = $closestIndex;
        $this->template->numOfEvents = count($this->template->events);
    }
}
