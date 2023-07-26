<?php

declare(strict_types=1);

namespace App\Components\TeamResults;

use Fykosak\NetteFKSDBDownloader\ORM\Models\ModelTeam;
use Fykosak\NetteFKSDBDownloader\ORM\Services\ServiceEventDetail;
use Fykosak\Utils\BaseComponent\BaseComponent;
use Nette\Application\UI\Form;
use Nette\DI\Container;
use Tracy\Debugger;

class TeamResultsComponent extends BaseComponent
{

    protected ServiceEventDetail $serviceTeam;
    protected int $eventId;
    protected ?array $filterData = null;
    private string $lang;

    public function __construct(Container $container, int $eventId, string $lang)
    {
        parent::__construct($container);
        $this->eventId = $eventId;
        $this->lang = $lang;
    }

    public function injectServiceTeam(ServiceEventDetail $serviceTeam): void
    {
        $this->serviceTeam = $serviceTeam;
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function render(): void
    {
        $this->template->teams = $this->loadTeams();
        $this->template->lang = $this->lang;
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'teamResults.latte');
    }

    /**
     * @return ModelTeam[][]
     * @throws \Throwable
     */
    protected function loadTeams(): array
    {

        $teams = [];
        foreach ($this->serviceTeam->getTeams($this->eventId) as $team) {
            if ($team->status != 'participated' && $team->status != 'disqualified') {
                continue;
            }
            if (is_null($this->filterData) || $this->passesFilters($team)) {
                $category = $team->category;
                if (!isset($teams[$category])) {
                    $teams[$category] = [];
                }
                $teams[$category][] = $team;
            }
        }

        ksort($teams);

        // remove categories that are empty after the filtering
        foreach ($teams as $category => $teamsForCategory) {
            if ($teamsForCategory == []) {
                unset($teams[$category]);
            }
        }

        return $teams;
    }

    protected function passesFilters(ModelTeam $team): bool
    {
        return $this->passesOneMemberFilter($team)
            && $this->passesCountryFilter($team);
    }

    protected function passesOneMemberFilter(ModelTeam $team): bool
    {
        return !$this->filterData['OneMemberTeams'] || count($team->members) == 1;
    }

    protected function passesCountryFilter(ModelTeam $team): bool
    {
        $ISOsForTeam = [];

        foreach ($team->members as $participant) {
            $iso = $participant->countryIso;
            if (!in_array($iso, $ISOsForTeam)) {
                $ISOsForTeam[] = $iso;
            }
        }

        // get selected ISOs
        $selectedISOs = [];
        foreach ($this->filterData['country_iso'] as $iso => $value) {
            if ($value) {
                $selectedISOs[] = $iso;
            }
        }


        if (empty($selectedISOs)) {
            return true;
        }

        foreach ($ISOsForTeam as $iso) {
            if (in_array($iso, $selectedISOs)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws \Throwable
     */
    protected function createComponentFilterForm(): Form
    {
        $form = new Form();

        $countryISOs = [];
        $categories = [];
        foreach ($this->serviceTeam->getTeams($this->eventId) as $team) {
            if ($team->status != 'participated' && $team->status != 'disqualified') {
                continue;
            }
            if ($team->members) {
                $category = $team->category;
                if (!in_array($category, $categories)) {
                    $categories[] = $category;
                }
                foreach ($team->members as $participant) {
                    if ($participant->countryIso) {
                        $countryISOs[$participant->countryIso] ??= 0;
                        $countryISOs[$participant->countryIso]++;
                    }
                }
            }
        }

        // one member teams
        $form->addCheckbox('OneMemberTeams', _('One member teams only'));

        // countries
        arsort($countryISOs);
        $countryISOContainer = $form->addContainer('country_iso');
        foreach ($countryISOs as $countryISO => $count) {
            $countryISOContainer->addCheckbox($countryISO, sprintf(_('%s:%s participants'), $countryISO, $count));
        }

        $form->addButton('reset')->setHtmlAttribute('type', 'reset')->setHtmlAttribute('class', 'btn btn-dark');

        $form->addSubmit('applyFilters', 'Apply')->setHtmlAttribute('class', 'btn btn-primary');

        $form->onSuccess[] = fn(Form $form) => $this->filterData = $form->getValues('array');

        return $form;
    }
}
