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
            'DSEF' => [
                'key' => 'dsef',
                'heading' => [
                    'cs' => 'Den s&nbsp;experimentální fyzikou',
                    'en' => 'Day with experimental physics'
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2024-10-21 09:00:00')),
                'show-in-en' => false,
                'is_series' => false,
                'url' => 'https://dsef.cz',
                'description' => [
                    'cs' => 'Den s&nbsp;experimentální fyzikou na Matfyzu',
                    'en' => 'Day with experimental physics at Matfyz'
                ],
                'show-on-timeline' => true,
                'logo_eventbox' => '/images/logos/dsef_logo.svg',
            ],
            'Naboj' => [
                'key' => 'naboj',
                'heading' => [
                    'cs' => 'Fyzikální Náboj',
                    'en' => 'Physics Náboj'
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2024-11-15 09:00:00')),
                'show-in-en' => false,
                'is_series' => false,
                'url' => 'https://physics.naboj.org',
                'description' => [
                    'cs' => 'Týmová soutěž v&nbsp;Praze, Ostravě a&nbsp;jinde ve&nbsp;světě',
                    'en' => 'Team competition in Prague, Ostrava, and elsewhere in the world'
                ],
                'show-on-timeline' => true,
                'logo_eventbox' => '/images/logos/naboj_logo.svg',
            ],
            'FOL' => [
                'key' => 'fol',
                'heading' => [
                    'cs' => 'Fyziklání Online',
                    'en' => 'Physics Brawl Online'
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2024-11-20 17:00:00')),
                'show-in-en' => true,
                'is_series' => false,
                'url' => 'https://online.fyziklani.cz',
                'description' => [
                    'cs' => 'Celosvětová soutěž, které se může účastnit každý',
                    'en' => 'Worldwide competition open to everyone'
                ],
                'show-on-timeline' => true,
                'logo_eventbox' => '/images/logos/fyziklani_online_logo.svg'
            ],
            'FOF' => [
                'key' => 'fof',
                'heading' => [
                    'cs' => 'Fyziklání',
                    'en' => 'Fyziklani'
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2025-02-14 10:30:00')),
                'show-in-en' => true,
                'is_series' => false,
                'url' => 'https://fyziklani.cz',
                'description' => [
                    'cs' => 'Největší týmová fyzikální soutěž v&nbsp;Evropě',
                    'en' => 'The largest team physics competition in Europe'
                ],
                'show-on-timeline' => true,
                'logo_eventbox' => '/images/logos/fyziklani_logo.svg'
            ],
            'serie-1' => [
                'key' => 'serie-1',
                'heading' => [
                    'cs' => 'Deadline 1.&nbsp;série',
                    'en' => 'Deadline Series&nbsp;1'
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2024-10-06 23:59:59')),
                'show-in-en' => true,
                'is_series' => true,
                'description' => [
                    'cs' => 'Rýže, kondenzátor a&nbsp;filodendron na cestě',
                    'en' => 'Rice, capacitor, and philodendron on the road'
                ],
                'show-on-timeline' => true
            ],
            'serie-2' => [
                'key' => 'serie-2',
                'heading' => [
                    'cs' => 'Deadline 2.&nbsp;série',
                    'en' => 'Deadline Series&nbsp;2'
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2024-11-24 23:59:59')),
                'show-in-en' => true,
                'is_series' => true,
                'description' => [
                    'cs' => 'Bitcoin, stromy a&nbsp;omrzování',
                    'en' => 'Bitcoin, trees, and cold exposure'
                ],
                'show-on-timeline' => true
            ],
            'serie-3' => [
                'key' => 'serie-3',
                'heading' => [
                    'cs' => 'Deadline 3.&nbsp;série',
                    'en' => 'Deadline Series&nbsp;3'
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2025-01-12 23:59:59')),
                'show-in-en' => true,
                'is_series' => true,
                'description' => [
                    'cs' => 'Chleba, starý zářič a&nbsp;aquapark',
                    'en' => 'Bread, radiation emitter, and water park'
                ],
                'show-on-timeline' => true
            ],
            'serie-4' => [
                'key' => 'serie-4',
                'heading' => [
                    'cs' => 'Deadline 4.&nbsp;série',
                    'en' => 'Deadline Series&nbsp;4'
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2025-02-23 23:59:59')),
                'show-in-en' => true,
                'is_series' => true,
                'description' => [
                    'cs' => 'Kouř, stín a&nbsp;raketou na zkoušku',
                    'en' => 'Smoke, shadow, and rocketing to an&nbsp;exam'
                ],
                'show-on-timeline' => true
            ],
            'serie-5' => [
                'key' => 'serie-5',
                'heading' => [
                    'cs' => 'Deadline 5.&nbsp;série',
                    'en' => 'Deadline Series&nbsp;5'
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2025-03-30 23:59:59')),
                'show-in-en' => true,
                'is_series' => true,
                'description' => [
                    'cs' => 'Čtverec, minigolf a vagon ve vesmíru',
                    'en' => 'Square, minigolf, and a wagon in space'
                ],
                'show-on-timeline' => true
            ],
            'serie-6' => [
                'key' => 'serie-6',
                'heading' => [
                    'cs' => 'Deadline 6.&nbsp;série',
                    'en' => 'Deadline Series&nbsp;6'
                ],
                'date' => date('Y-m-d H:i:s', strtotime('2025-05-11 23:59:59')),
                'show-in-en' => true,
                'is_series' => true,
                'description' => [
                    'cs' => 'Akční film, ponorka a&nbsp;ořezávání tužky',
                    'en' => 'Action movie, submarine, and pencil sharpening'
                ],
                'show-on-timeline' => true
            ]
        ];
        $this->template->timelineBegin = date('Y-m-d', strtotime('2024-09-01'));
        $this->template->timelineEnd = date('Y-m-d', strtotime('2025-05-31'));

        // sort chronologically
        usort($this->template->events, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
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
