<?php

namespace App\Components\TeamResults;

use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\Application\UI\Form;
use Nette\DI\Container;

class TeamResultsComponent extends BaseComponent {

    protected ServiceEventDetail $serviceTeam;
    protected int $eventId;

    public function __construct(Container $container, int $eventId) {
        parent::__construct($container);
        $this->eventId = $eventId;
    }

    public function injectServiceTeam(ServiceEventDetail $serviceTeam): void {
        $this->serviceTeam = $serviceTeam;
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function render(): void {
        $teams = [];
        foreach ($this->serviceTeam->getTeams($this->eventId) as $team) {
            $category = $team->category;
            if (!isset($teams[$category])) {
                $teams[$category] = [];
            }
            $teams[$category][] = $team;
        }
        $this->template->teams = $teams;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'teamResults.latte');
    }

    protected function createComponentFilterForm(): Form {
        $form = new Form();
        $form->addSelect('category',);
        $countryISOs = [];
        $categories = [];
        foreach ($this->serviceTeam->getTeams($this->eventId) as $team) {
            if ($team->participants) {
                foreach ($team->participants as $participant) {
                    $countryISOs[$participant->countryIso] ??= 0;
                    $countryISOs[$participant->countryIso]++;
                }
            }
        }
        arsort($countryISOs);
        $countryISOContainer = $form->addContainer('country_iso');
        foreach ($countryISOs as $countryISO => $count) {
            $countryISOContainer->addCheckbox($countryISO, sprintf(_('%s:%s participants'), $countryISO, $count));
        }

        return $form;
    }
}
