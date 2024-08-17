<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

class DefaultPresenter extends BasePresenter
{
    public function renderDefault(): void
    {
        $this->template->newsList = $this->loadNews();

        $this->loadEventData();

        $currentDate = strtotime(date('Y-m-d'));
        // year_stage is enum of values: 'before', 'during', 'after'
        $this->template->year_stage = null;
        if ($currentDate < strtotime($this->template->timelineBegin)) {
            $this->template->year_stage = 'before';
        } elseif ($currentDate > strtotime($this->template->timelineEnd)) {
            $this->template->year_stage = 'after';
        } else {
            $this->template->year_stage = 'during';
        }

        if ($this->template->year_stage === 'during') {
            // Sort events by date
            usort($this->template->events, function ($a, $b) {
                $dateA = strtotime($a['date']);
                $dateB = strtotime($b['date']);
                return $dateA - $dateB;
            });

            // Find the closest event
            $this->template->countdownEventsIndices = $this->findCountdownEventIndices($this->template->events);

            $this->template->numOfEvents = [
                'cs' => count($this->template->events),
                'en' => count(array_filter($this->template->events, function ($event) {
                    return $event['show-in-en'];
                }))
            ];
        }
    }

    public function loadNews(): array
    {
        // load json
        $json = file_get_contents(__DIR__ . '/templates/Default/news.json');
        $newsList = json_decode($json, true);

        // implement colors
        foreach ($newsList[$this->language->value] as &$news) {
            switch ($news['color']) {
                case 'fof':
                    $news['color'] = '#e6060d';
                    break;
                case 'fol':
                    $news['color'] = '#00ae6b';
                    break;
                case 'fykos':
                    $news['color'] = '#1175da';
                    break;
                case 'dsef':
                    $news['color'] = '#f2b72b';
                    break;
            }
        }

        return $newsList;
    }

    public function loadEventData(): void
    {
        $this->template->events = [
            'Naboj' => [
                'heading' => [
                    'cs' => 'Fyzikální Náboj',
                    'en' => null
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2023-11-03 09:00:00')),
                'show-in-en' => false
            ],
            'DSEF' => [
                'heading' => [
                    'cs' => 'DSEF',
                    'en' => null
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2023-11-06 09:00:00')),
                'show-in-en' => false
            ],
            'FOL' => [
                'heading' => [
                    'cs' => 'Fyziklání Online',
                    'en' => 'Physics Brawl Online'
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2023-11-21 17:00:00')),
                'show-in-en' => true
            ],
            'FOF' => [
                'heading' => [
                    'cs' => 'Fyziklání',
                    'en' => 'Fyziklani'
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2024-02-16 10:30:00')),
                'show-in-en' => true
            ],
            'serie-1' => [
                'heading' => [
                    'cs' => 'Deadline 1. série',
                    'en' => 'Deadline Series 1'
                ],
                    'date' => date('Y-m-d H:i:s', strtotime('2023-10-10 23:59:59')),
                    'show-in-en' => true
                ],
                'serie-2' => [
                    'heading' => [
                        'cs' => 'Deadline 2. série',
                        'en' => 'Deadline Series 2'
                    ],
                    'date' => date('Y-m-d H:i:s', strtotime('2023-11-21 23:59:59')),
                    'show-in-en' => true
                ],
                'serie-3' => [
                    'heading' => [
                        'cs' => 'Deadline 3. série',
                        'en' => 'Deadline Series 3'
                    ],
                    'date' => date('Y-m-d H:i:s', strtotime('2024-01-02 23:59:59')),
                    'show-in-en' => true
                ],
                'serie-4' => [
                    'heading' => [
                        'cs' => 'Deadline 4. série',
                        'en' => 'Deadline Series 4'
                    ],
                    'date' => date('Y-m-d H:i:s', strtotime('2024-02-27 23:59:59')),
                    'show-in-en' => true
                ],
                'serie-5' => [
                    'heading' => [
                        'cs' => 'Deadline 5. série',
                        'en' => 'Deadline Series 5'
                    ],
                    'date' => date('Y-m-d H:i:s', strtotime('2024-04-09 23:59:59')),
                    'show-in-en' => true
                ],
                'serie-6' => [
                    'heading' => [
                        'cs' => 'Deadline 6. série',
                        'en' => 'Deadline Series 6'
                    ],
                    'date' => date('Y-m-d H:i:s', strtotime('2024-05-14 23:59:59')),
                    'show-in-en' => true
            ]
        ];
        $this->template->timelineBegin = date('Y-m-d', strtotime('2023-09-01'));
        $this->template->timelineEnd = date('Y-m-d', strtotime('2024-05-31'));
    }

    public function findCountdownEventIndices(array $events): array
    {
        // Find the event with the closest date larger than the current date
        $currentDate = strtotime(date('Y-m-d'));
        $closestIndex = 0;
        $closestIndexEn = 0;

        foreach ($this->template->events as $event) {
            $eventDate = strtotime($event['date']);

            if ($eventDate < $currentDate) {
                $closestIndex += 1;
                $closestIndexEn += 1;
            } else {
                break;
            }
        }

        $upcomingIndex = $closestIndex;
        $previousIndex = ($closestIndex - 1 >= 0) ? $closestIndex - 1 : null;
        $nextIndex = ($closestIndex + 1 < count($this->template->events)) ? $closestIndex + 1 : null;

        $upcomingIndexEn = $closestIndexEn;

        // If the closest event is not shown in English, find the next event that is
        if (!$this->template->events[$closestIndex]['show-in-en']) {
            for ($i = $closestIndexEn + 1; $i < count($this->template->events); $i++) {
                if ($this->template->events[$i]['show-in-en']) {
                    $upcomingIndexEn = $i;
                    break;
                }
            }
        }

        $previousIndexEn = null;
        $nextIndexEn = null;

        // Find the previous event with $event['show-in-en'] == true
        for ($i = $closestIndexEn - 1; $i >= 0; $i--) {
            if ($this->template->events[$i]['show-in-en']) {
                $previousIndexEn = $i;
                break;
            }
        }

        // Find the next event with $event['show-in-en'] == true
        for ($i = $closestIndexEn + 1; $i < count($this->template->events); $i++) {
            if ($this->template->events[$i]['show-in-en']) {
                $nextIndexEn = $i;
                break;
            }
        }

        return [
            'previous' => [
                'cs' => $previousIndex,
                'en' => $previousIndexEn,
            ],
            'upcoming' => [
                'cs' => $upcomingIndex,
                'en' => $upcomingIndexEn,
            ],
            'next' => [
                'cs' => $nextIndex,
                'en' => $nextIndexEn
            ],
        ];
    }
}
