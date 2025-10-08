<?php

declare(strict_types=1);

namespace App\Modules\Vyfuk\DefaultModule;

use Fykosak\FKSDBDownloaderCore\Requests\SeriesResultsRequest;

class ResultsPresenter extends BasePresenter
{
    public ?int $year = null;

    /**
     * @throws \Throwable
     */
    public function renderDefault(): void
    {
        $year = $this->getPointsYear();
        $year = $this->year ?? ($year ? $year->year : 1);
        $this->template->year = $year;
        $this->template->contest = $this->getContest();
        $results = $this->downloader->download(new SeriesResultsRequest($this->getContestId(), $year));
        $tasksHasResult = [];
        foreach ($results['submits'] as $category => $contestants) {
            foreach ($contestants as $key => $contestant) {
                foreach ($contestant['submits'] as $taskId => $points) {
                    if ($points === null) {
                        unset($results['submits'][$category][$key]['submits'][$taskId]);
                    } else {
                        $tasksHasResult[$taskId] = true;
                    }
                }
                if (count($results['submits'][$category][$key]['submits']) == 0) {
                    unset($results['submits'][$category][$key]);
                }
            }
            $results['submits'][$category] = array_values($results['submits'][$category]);
        }
        foreach ($results["tasks"] as $category => $series) {
            foreach ($series as $serieNumber => $tasks) {
                foreach ($tasks as $taskOrder => $task) {
                    if (!isset($tasksHasResult[$task['taskId']])) {
                        unset($results['tasks'][$category][$serieNumber][$taskOrder]);
                    }
                }
                if (count($results['tasks'][$category][$serieNumber]) == 0) {
                    unset($results['tasks'][$category][$serieNumber]);
                } else {
                    $results['tasks'][$category][$serieNumber] = array_values($results['tasks'][$category][$serieNumber]);
                }
            }
        }
        $series = [];
        foreach ($results["tasks"] as $s) {
            $series = array_merge($series, array_keys($s));
        }
        $series = array_unique($series);
        $this->template->results = $results;
        $this->template->series = $series;
    }
}
