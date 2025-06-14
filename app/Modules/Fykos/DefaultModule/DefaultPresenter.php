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

        // Sort events by date
        usort($this->template->events, function ($a, $b) {
            $dateA = strtotime($a['date']);
            $dateB = strtotime($b['date']);
            return $dateA - $dateB;
        });

        // year_stage is enum of values: 'before', 'during', 'after'
        $this->template->year_stage = null;
        if ($currentDate < strtotime($this->template->events[0]['date'])) {
            $this->template->year_stage = 'before';
        } elseif ($currentDate > strtotime($this->template->events[count($this->template->events) - 1]['date'])) {
            $this->template->year_stage = 'after';
        } else {
            $this->template->year_stage = 'during';
        }

        if ($this->template->year_stage === 'during') {
            // Find the closest event
            $this->template->countdownEventsIndices = $this->findCountdownEventIndices($this->template->events);

            $this->template->numOfEvents = [
                'cs' => count($this->template->events),
                'en' => count(array_filter($this->template->events, function ($event) {
                    return $event['show-in-en'];
                }))
            ];
        }

        $this->template->headerText = $this->getHeaderText();
    }

    public function getHeaderText(): array
    {
        $headerTextOptions = [
            [
                'cs' => [
                    'slogan' => 'Zažijte fyziku s&nbsp;námi!',
                    'description' => 'Organizujeme pro vás neziskové vzdělávací akce ve fyzice již ' . $this->getCurrentYear()->year . ' let.'
                ],
                'en' => [
                    'slogan' => 'Experience physics with us!',
                    'description' => 'FYKOS has been organizing non-profit educational events in physics for ' . $this->getCurrentYear()->year . ' years.'
                ]
            ]
        ];

        # Choose randomly from the options
        return $headerTextOptions[array_rand($headerTextOptions)];
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
                case 'naboj':
                    $news['color'] = '#c22d86';
            }
        }

        return $newsList;
    }

    private function fmtDate(string $date): string {
        return date('Y-m-d H:i:s', strtotime($date));
    }

    public function loadEventData(): void
    {
        $this->template->events = [];
        $events = [
            'DSEF' => [
                'date' => '2025-11-10 09:00:00',
                'english' => false,
                'url' => 'https://dsef.cz',
                'heading' => [
                    'cs' => 'Den s&nbsp;experimentální fyzikou',
                    'en' => 'Day with experimental physics'
                ],
                'desc' => [
                    'cs' => 'Den s&nbsp;experimentální fyzikou na Matfyzu',
                    'en' => 'Day with experimental physics at Matfyz'
                ],
                'logo' => [
                    'cs' => '/images/logos/dsef_logo.svg'
                ]
            ],
            'Naboj' => [
                'date' => '2025-11-07 09:00:00',
                'english' => false,
                'url' => 'https://physics.naboj.org',
                'heading' => [
                    'cs' => 'Fyzikální Náboj',
                    'en' => 'Physics Náboj'
                ],
                'desc' => [
                    'cs' => 'Týmová soutěž v&nbsp;Praze, Ostravě a&nbsp;jinde ve&nbsp;světě',
                    'en' => 'Team competition in Prague, Ostrava, and elsewhere in the world'
                ],
                'logo' => [
                    'cs' => '/images/logos/naboj_logo.svg'
                ]
            ],
            'FOL' => [
                'date' => '2025-11-26 17:00:00',
                'english' => true,
                'url' => 'https://online.fyziklani.cz',
                'heading' => [
                    'cs' => 'Fyziklání Online',
                    'en' => 'Physics Brawl Online'
                ],
                'desc' => [
                    'cs' => 'Celosvětová soutěž, které se může účastnit každý',
                    'en' => 'Worldwide competition open to everyone'
                ],
                'logo' => [
                    'cs' =>  '/images/logos/fyziklani_online_logo.svg',
                    'en' => '/images/logos/physics_brawl_online_logo.svg'
                ]
            ],
            'FOF' => [
                'date' => '2026-02-13 10:30:00',
                'english' => true,
                'url' => 'https://fyziklani.cz',
                'heading' => [
                    'cs' => 'Fyziklání',
                    'en' => 'Fyziklani'
                ],
                'desc' => [
                    'cs' => 'Největší týmová fyzikální soutěž v&nbsp;Evropě',
                    'en' => 'The largest team physics competition in Europe'
                ],
                'logo' => [
                    'cs' =>  '/images/logos/fyziklani_logo.svg',
                    'en' =>  '/images/logos/fyziklani_logo.svg'
                ]
            ],
        ];
        $series = [
            /*
            '1' => [
                'deadline' => '2025-10-05',
                'description' => [
                    'cs' => 'Bude oznámeno',
                    'en' => 'To be announced'
                ],
            ],
            '2' => [
                'deadline' => '2000-01-01',
                'description' => [
                    'cs' => 'Bude oznámeno',
                    'en' => 'To be announced'
                ],
            ],
            '3' => [
                'deadline' => '2000-01-01',
                'description' => [
                    'cs' => 'Bude oznámeno',
                    'en' => 'To be announced'
                ],
            ],
            '4' => [
                'deadline' => '2000-01-01',
                'description' => [
                    'cs' => 'Bude oznámeno',
                    'en' => 'To be announced'
                ],
            ],
            '5' => [
                'deadline' => '2000-01-01',
                'description' => [
                    'cs' => 'Bude oznámeno',
                    'en' => 'To be announced'
                ],
            ],
            '6' => [
                'deadline' => '2000-01-01',
                'description' => [
                    'cs' => 'Bude oznámeno',
                    'en' => 'To be announced'
                ],
            ],
            */
        ];
        foreach ($events as $id => $data) {
            $key = strtolower($id);
            $this->template->events[$key] = [
                'key' => $key,
                'heading' => $data['heading'],
                'date' => $this->fmtDate($data['date']),
                'show-in-en' => $data['english'],
                'is_series' => false,
                'url' => $data['url'],
                'description' => $data['desc'],
                'show-on-timeline' => true,
                'logo_eventbox' => $data['logo'],
            ];
        }
        /** @phpstan-ignore */
        foreach ($series as $id => $data) {
            $key = 'serie-' . $id;
            $this->template->events[$key] = [
                'key' => $key,
                'heading' => [
                    'cs' => 'Deadline ' . $id . '.&nbsp;série',
                    'en' => 'Deadline Series&nbsp;' . $id
                ],
                'date' => $this->fmtDate($data['deadline'] . ' 23:59:59'),
                'show-in-en' => true,
                'is_series' => true,
                'description' => $data['description'],
                'show-on-timeline' => true
            ];
        }
        $this->template->timelineBegin = date('Y-m-d', strtotime('2025-09-01'));
        $this->template->timelineEnd = date('Y-m-d', strtotime('2026-05-31'));

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
        for ($i = $upcomingIndexEn - 1; $i >= 0; $i--) {
            if ($this->template->events[$i]['show-in-en']) {
                $previousIndexEn = $i;
                break;
            }
        }

        // Find the next event with $event['show-in-en'] == true
        for ($i = $upcomingIndexEn + 1; $i < count($this->template->events); $i++) {
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
