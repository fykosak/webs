<?php

declare(strict_types=1);

namespace App\Modules\Fykos\DefaultModule;

use App\Components\OrgSneakPeak\OrgSneakPeakComponent;

class YearPresenter extends BasePresenter
{
    public $year = 36; // todo get from url

    // read from JSON
    public function getYearData(int $year): array
    {
        $json = file_get_contents(__DIR__ . "/templates/Year/YearData/$year.json");
        $data = json_decode($json, true);

        $data = $this->parseYearData($data);

        return $data;
    }

    public function parseYearData(array $data): array
    {
        foreach ($data['events'] as &$event) {
            if (!isset($event['name'])) {
                $event['name'] = $event['type'];
                switch ($event['type']) {
                    case 'fof':
                        $event['name'] = 'Fyziklání';
                        break;
                    case 'naboj':
                        $event['name'] = 'Fyzikální Náboj';
                        break;
                    case 'fol':
                        $event['name'] = 'Fyziklání Online';
                        break;
                    case 'sous-jaro':
                        $event['name'] = "Jarní soustředění {$this->year}. ročníku";
                        break;
                    case 'sous-podzim':
                        $event['name'] = "Podzimní soustředění {$this->year}. ročníku";
                        break;
                    case 'dsef':
                        $event['name'] = 'Den s experimentální fyzikou';
                        break;
                }
            };
            $event['img-path'] = "/images/events/default/fykos.png";
            $event["n-href-url"] = "/images/events/default/fykos.png";
        }
        return $data;
    }

    public function createComponentOrgSneakPeak(): OrgSneakPeakComponent
    {
        return new OrgSneakPeakComponent($this->getContext());
    }

    public function renderDefault(): void
    {
        $this->template->data = $this->getYearData($this->year);
        $this->template->year = $this->year;
    }
}
