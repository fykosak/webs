import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import './style.scss';

interface Contestant {
    contestant: {
        contestantId: number; name: string; school: string;
    };
    rank: [number, number];
    submits: {
        [key: number]: number;
    }
    sum: number;
}

type Submits = Array<Contestant>;

interface Task {
    label: string;
    points: number;
    taskId: number;
}

type Tasks = {
    [series: number]: Array<Task>;
};

interface Props<Category extends string = string> {
    resultsData: {
        submits: {
            [key in Category]: Submits;
        }
        tasks: {
            [key in Category]: Tasks;
        }
    };
}

function Results({resultsData}: Props) {
    const categoryContainers = [];
    for (const category in resultsData.submits) {
        categoryContainers.push(<CategoryResults
            submits={resultsData.submits[category]}
            tasks={resultsData.tasks[category]}
        />);
    }
    return <div>{categoryContainers}</div>;
}

function CategoryResults({submits, tasks}: { submits: Submits, tasks: Tasks }) {
    const [activeSeries, setActiveSeries] = useState<{ [key: string]: boolean }>({});
    const head = [];
    for (const series in tasks) {
        const tasksInSeries = tasks[series];
        const active = activeSeries[series] || false;
        if (active) {
            const subTasks = tasksInSeries.map((task) => {
                return <td>{task.label}({task.points})</td>;
            });
            head.push(<>{subTasks}</>);
        }
        head.push(<th onClick={() => setActiveSeries({...activeSeries, [series]: !active})}>Series {series}</th>);

    }
    return <table className="table table-hover contest-results table-sm">
        <thead>
        <tr>
            <th>Name</th>
            <th>School</th>
            {head}
        </tr>
        </thead>
        <tbody>{submits.map((contestant) => {
            const seriesContainers = [];
            for (const series in tasks) {
                const tasksInSeries = tasks[series];
                seriesContainers.push(<SeriesResults
                    series={series}
                    key={series}
                    tasks={tasksInSeries}
                    contestant={contestant}
                    show={activeSeries[series] || false}
                />);
            }
            return <tr>
                <td>{contestant.contestant.name}</td>
                <td>{contestant.contestant.school}</td>
                {seriesContainers}
            </tr>;
        })}</tbody>
    </table>;
}

interface SeriesResultsProps {
    tasks: Array<Task>,
    contestant: Contestant,
    show: boolean,
    series: string
}

function SeriesResults(
    {
        tasks,
        contestant,
        show,
        series,
    }: SeriesResultsProps,
) {
    let sum = 0;
    const subTasks = tasks.map((task) => {
        let badge;
        if (contestant.submits.hasOwnProperty(task.taskId)) {
            if (contestant.submits[task.taskId] === null) {
                badge = <span className="points points-na">?</span>;
            } else {
                sum += contestant.submits[task.taskId];
                badge =
                    <span className="points points-ok">{contestant.submits[task.taskId]}</span>;
            }
        } else {
            badge = <span className="points points-no">-</span>;
        }
        return <td data-series={series} key={task.label} data-label={task.label}>{badge}</td>
    });
    return <>{show && subTasks}
        <td data-series={series}><strong>{sum}</strong></td>
    </>;
}

document.addEventListener('DOMContentLoaded', () => {
    const element = document.getElementById('contest-results');
    if (element) {
        const data = JSON.parse(element.getAttribute('data-data'));
        ReactDOM.render(<Results resultsData={data}/>, element);
    }
});


