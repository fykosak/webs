<?php

namespace App\Components\TeamResults;

use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelTeam;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\Application\UI\Form;
use Nette\DI\Container;
use Nette\Utils\ArrayHash;

class TeamResultsComponent extends BaseComponent {

    protected ServiceEventDetail $serviceTeam;
    protected int $eventId;

    protected string $ALL_CATEGORIES_IDENTIFIER = "All";
    protected  ?ArrayHash $filterData = Null;

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

        $this->template->teams = $this->loadTeams();

        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'teamResults.latte');
    }

    protected function loadTeams(){

        $teams = [];
        foreach ($this->serviceTeam->getTeams($this->eventId) as $team) {

            if ($team->status != 'participated') continue;

            if (is_null($this->filterData) || $this->passesFilters($team)){
                $category = $team->category;
                if (!isset($teams[$category])){
                    $teams[$category] = [];
                }
                $teams[$category][] = $team;
            }
        }

        ksort($teams);

//      remove categories that are empty after the filtering
        foreach ($teams as $category => $teamsForCategory){
            if (empty($teamsForCategory)){
                unset($teams[$category]);
            }
        }

        return $teams;
    }

    protected function passesFilters(ModelTeam $team){
        return ($this->passesOneMemberFilter($team) && $this->passesCountryFilter($team) && $this->passesCategoryFilter($team)  );
    }

    protected function passesOneMemberFilter(ModelTeam $team){
        return (!$this->filterData["OneMemberTeams"] || ($this->filterData["OneMemberTeams"] && count($team->participants)  == 1));
    }

    protected function passesCountryFilter(ModelTeam $team)
    {
        $ISOsForTeam = [];

        foreach ($team->participants as $participant) {
            $ISO =  $participant->countryIso;
            if (!in_array($ISO,$ISOsForTeam)){
                $ISOsForTeam[] = $ISO;
            }
        }

        // get selected ISOs
        $selectedISOs = [];
        foreach ($this->filterData["country_iso"] as $ISO => $value){
            if ($value){
                $selectedISOs[] = $ISO;
            }
        }


        if (empty($selectedISOs)){
            return true;
        }

        foreach ($ISOsForTeam as $ISO){
            if (in_array($ISO,$selectedISOs)){
                return true;
            }
        }

        return false;
    }

    protected function passesCategoryFilter(ModelTeam $team){
        return (($this->filterData["category"] == $this->ALL_CATEGORIES_IDENTIFIER) || ($this->filterData["category"] == $team->category));
    }


    protected function createComponentFilterForm(): Form {
        $form = new Form();

        $countryISOs = [];
        $categories = [];
        foreach ($this->serviceTeam->getTeams($this->eventId) as $team) {
            if ($team->participants) {
                $category = $team->category;
                if (!in_array($category,$categories)) {
                    $categories[] = $category;
                }
                foreach ($team->participants as $participant) {
                    $countryISOs[$participant->countryIso] ??= 0;
                    $countryISOs[$participant->countryIso]++;
                }
            }
        }

        // one member teams
        $form->addCheckbox('OneMemberTeams',"One-member teams");

        // categories
        asort($categories);
        array_unshift($categories,$this->ALL_CATEGORIES_IDENTIFIER);
        $form->addSelect('category',"category",$categories);

        // countries
        arsort($countryISOs);
        $countryISOContainer = $form->addContainer('country_iso');
        foreach ($countryISOs as $countryISO => $count) {
            $countryISOContainer->addCheckbox($countryISO, sprintf(_('%s:%s participants'), $countryISO, $count));
        }


        $form->addSubmit('applyFilters', 'Apply');

        $form->addButton('reset')->setHtmlAttribute('type', 'reset');

        $form->onSuccess[] = [$this, 'filterFormSucceeded'];

        return $form;
    }

    public function filterFormSucceeded(Form $form, $data){
        // redefine data for the chosen category
        $data["category"] = $form["category"]->getSelectedItem();

        $this->filterData = $data;
    }

}
