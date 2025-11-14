<?php

declare(strict_types=1);

namespace App\Components\TeamResults;

use App\Models\Downloader\Services\DummyService;
use App\Models\Downloader\Models\EventModel;
use App\Models\Downloader\Models\TeamModel;
use App\Modules\Core\Language;
use Fykosak\FKSDBDownloaderCore\Requests\TeamsRequest;
use Fykosak\Utils\Components\DIComponent;
use Nette\Application\UI\Form;
use Nette\DI\Container;

class TeamResultsComponent extends DIComponent
{
    protected readonly DummyService $serviceTeam;
    protected ?array $filterData = null;

    public function __construct(
        Container $container,
        protected readonly EventModel $event
    ) {
        parent::__construct($container);
    }

    public function injectServiceTeam(DummyService $serviceTeam): void
    {
        $this->serviceTeam = $serviceTeam;
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function render(): void
    {
        $this->template->lang = $this->translator->lang;
        if (!$this->event->game->hardVisible) {
            $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'teamResultsHidden.latte');
            return;
        }
        // $this->filterData = $this->getParameter('filterData');
        // $this->template->filterData = $this->filterData;
        $this->template->teams = $this->loadTeams();
        $this->template->render(__DIR__ . DIRECTORY_SEPARATOR . 'teamResults.latte');
    }

    /**
     * @return TeamModel[][]
     * @throws \Throwable
     */
    protected function loadTeams(): array
    {

        $teams = [];
        foreach ($this->serviceTeam->get(new TeamsRequest($this->event->eventId), TeamModel::class) as $team) {
            if ($team->state !== 'participated' && $team->state !== 'disqualified') {
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
            /** @phpstan-ignore-next-line*/
            if (!count($teamsForCategory)) {
                unset($teams[$category]);
            }
        }

        return $teams;
    }

    protected function passesFilters(TeamModel $team): bool
    {
        return $this->passesOneMemberFilter($team)
            && $this->passesCountryFilter($team);
    }

    protected function passesOneMemberFilter(TeamModel $team): bool
    {
        return !$this->filterData['OneMemberTeams'] || count($team->members) === 1;
    }

    protected function passesCountryFilter(TeamModel $team): bool
    {
        $ISOsForTeam = [];

        foreach ($team->members as $member) {
            $iso = $member->school['countryISO'] ?? 'Uknown';
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


        if (count($selectedISOs) === 0) {
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
        foreach ($this->serviceTeam->get(new TeamsRequest($this->event->eventId), TeamModel::class) as $team) {
            if ($team->state !== 'participated' && $team->state !== 'disqualified') {
                continue;
            }
            if ($team->members) {
                $category = $team->category;
                if (!in_array($category, $categories)) {
                    $categories[] = $category;
                }
                foreach ($team->members as $member) {
                    if (isset($member->school)) {
                        $countryISOs[$member->school['countryISO']] ??= 0;
                        $countryISOs[$member->school['countryISO']]++;
                    }
                }
            }
        }

        // one member teams
        $form->addCheckbox(
            'OneMemberTeams',
            $this->translator->lang === Language::cs ? 'Pouze jednočlenné týmy' : 'One member teams only'
        );

        // countries
        arsort($countryISOs);

        $countryISOContainer = $form->addContainer('country_iso');
        // foreach ($countryISOs as $countryISO => $count) {
        //     $countryISOContainer->addCheckbox($countryISO, $countryISO)
        //         ->setOption('count', $count);
        // }
        foreach ($countryISOs as $countryISO => $count) {
            $countryISO = $countryISO !== '' ? $countryISO : 'Uknown'; // set default value to unknown countries
            $countryISOContainer->addCheckbox(
                $countryISO,
                sprintf(
                    $this->translator->lang === Language::cs ? ' %s: %s účastníků' : ' %s: %s participants',
                    /** @phpstan-ignore-next-line */
                    $countryISO !== 'Uknown' ? $countryISO : $this->presenter->csen('Nestudent', 'Not a student'),
                    $count
                )
            );
        }


        $form->addButton('reset')->setHtmlAttribute('type', 'reset')->setHtmlAttribute('class', 'btn btn-dark');

        $form->addSubmit('applyFilters', 'Apply')->setHtmlAttribute('class', 'btn btn-primary');

        $form->onSuccess[] = fn(Form $form) => $this->filterData = $form->getValues('array');
        // $form->onSuccess[] = function(Form $form) {
        //     $this->filterData = $form->getValues('array');
        //     $this->redirect('this', ['filterData' => $this->filterData]);
        // };

        // $form->setRenderer(new \App\Renderers\CustomFormRenderer($this->lang));

        return $form;
    }
}
