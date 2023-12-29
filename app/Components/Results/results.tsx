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

function CategoryResults({ submits, tasks }: { submits: Submits, tasks: Tasks }) {
    const [activeSeries, setActiveSeries] = useState<{ [key: string]: boolean }>({});
    const [sortColumn, setSortColumn] = useState<string | null>('Rank');
    const [sortDirection, setSortDirection] = useState<'asc' | 'desc'>('asc');

    const toggleSort = (column: string) => {
        if (sortColumn === column) {
            setSortDirection(sortDirection === 'asc' ? 'desc' : 'asc');
        } else {
            setSortColumn(column);
            setSortDirection('asc');
        }
    };

    const sortedSubmits = React.useMemo(() => {
        if (sortColumn) {
            const sorted = [...submits];
            sorted.sort((a, b) => {
                if (sortColumn === 'Name') {
                    const lastNameA = a.contestant.name.split(' ').pop();
                    const lastNameB = b.contestant.name.split(' ').pop();
                    return sortDirection === 'asc' ? lastNameA.localeCompare(lastNameB) : lastNameB.localeCompare(lastNameA);
                } else if (sortColumn === 'School') {
                    return sortDirection === 'asc' ? a.contestant.school.localeCompare(b.contestant.school) : b.contestant.school.localeCompare(a.contestant.school);
                } else if (sortColumn === 'Rank') {
                    return sortDirection === 'asc' ? a.rank[0] - b.rank[0] : b.rank[0] - a.rank[0];
                }
                return 0;
            });
            return sorted;
        }
        return submits;
    }, [submits, sortColumn, sortDirection]);

    const head = [];
    for (const series in tasks) {
        const tasksInSeries = tasks[series];
        const active = activeSeries[series] || false;
        if (active) {
            const subTasks = tasksInSeries.map((task, taskIndex) => {
                return (
                    <td className={`centered-cell ${taskIndex === 0 ? 'border-left' : ''} ${taskIndex === tasksInSeries.length - 1 ? 'border-right' : ''}`}>
                        <span className="task-label-header">{task.label}</span> <br></br>
                        <span data-label={task.label} className="points points-ok">{task.points}</span>
                    </td>
                );
            });
            head.push(<>{subTasks}</>);
        }
        head.push(
            <th
                onClick={() => {
                    const newActiveSeries = { [series]: !active };
                    for (const s in activeSeries) {
                        if (s !== series) {
                            newActiveSeries[s] = false;
                        }
                    }
                    setActiveSeries(newActiveSeries);
                }}
                className="centered-cell clickable-header"
            >
                s{series} <br></br> {active ? <span className="inactive-arrow"><>&#8594;</></span> : <span className="inactive-arrow"><>&#8592;</></span>}
            </th>
        );
    }

    return (
        <table className="table table-hover contest-results table-sm">
            <thead>
                <tr>
                    <th className="centered-cell clickable-header" onClick={() => toggleSort('Rank')}>
                            Rank
                            {sortColumn === 'Rank' ? (
                                <span style={{ color: 'black' }}>
                                    {sortDirection === 'asc' ? '↓' : '↑'}
                                </span>
                            ) : (
                                <span className="inactive-arrow">
                                    ↓
                                </span>
                            )}
                    </th>
                    <th className="centered-cell clickable-header" onClick={() => toggleSort('Name')}>
                        Name
                        {sortColumn === 'Name' ? (
                            <span style={{ color: 'black' }}>
                                {sortDirection === 'asc' ? '↓' : '↑'}
                            </span>
                        ) : (
                            <span className="inactive-arrow">
                                ↓
                            </span>
                        )}
                    </th>
                    <th
                        onClick={() => toggleSort('School')}
                        className="centered-cell clickable-header"
                    >
                        School
                        {sortColumn === 'School' ? (
                            <span style={{ color: 'black' }}>
                                {sortDirection === 'asc' ? '↓' : '↑'}
                            </span>
                        ) : (
                            <span className="inactive-arrow">
                                ↓
                            </span>
                        )}
                    </th>
                    {head}
                </tr>
            </thead>
            <tbody>
                {sortedSubmits.map((contestant) => {
                    const seriesContainers = [];
                    for (const series in tasks) {
                        const tasksInSeries = tasks[series];
                        seriesContainers.push(
                            <SeriesResults
                                series={series}
                                key={series}
                                tasks={tasksInSeries}
                                contestant={contestant}
                                show={activeSeries[series] || false}
                            />
                        );
                    }
                    return (
                        <tr>
                            <td className="centered-cell">{contestant.rank[0]}</td>
                            <td>{contestant.contestant.name}</td>
                            <td>{contestant.contestant.school}</td>
                            {seriesContainers}
                        </tr>
                    );
                })}
            </tbody>
        </table>
    );
}

interface SeriesResultsProps {
    tasks: Array<Task>;
    contestant: Contestant;
    show: boolean;
    series: string;
}

function SeriesResults({
    tasks,
    contestant,
    show,
    series,
}: SeriesResultsProps) {
    let sum = 0;
    const subTasks = tasks.map((task, index) => {
        let badge;
        if (contestant.submits.hasOwnProperty(task.taskId)) {
            if (contestant.submits[task.taskId] === null) {
                badge = <span className="points points-na">?</span>;
            } else {
                sum += contestant.submits[task.taskId];
                badge = <span className="points points-ok">{contestant.submits[task.taskId]}</span>;
            }
        } else {
            badge = <span className="points points-no">-</span>;
        }
        const isFirstSeries = index === 0;
        const isLastSeries = index === tasks.length - 1;
        const classNames = `centered-cell ${isFirstSeries ? 'border-left' : ''} ${isLastSeries ? 'border-right' : ''}`;
        return (
            <td data-series={series} key={task.label} data-label={task.label} className={classNames}>
                {badge}
            </td>
        );
    });
    return (
        <>
            {show && subTasks}
            <td data-series={series} className="centered-cell">
                <strong>{sum}</strong>
            </td>
        </>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const element = document.getElementById('contest-results');
    if (element) {
        const data = JSON.parse(element.getAttribute('data-data'));
        ReactDOM.render(<Results resultsData={data} />, element);
    }
});

function Results({ resultsData }: Props) {
    const [activeCategories, setActiveCategories] = useState<{ [key: string]: boolean }>({});

    const toggleCategory = (category: string) => {
        setActiveCategories((prevActiveCategories) => ({
            ...prevActiveCategories,
            [category]: !prevActiveCategories[category],
        }));
    };

    const sortedCategories = Object.keys(resultsData.submits).sort((a, b) => {
        const categoryNumberA = parseInt(a.slice(-1), 10);
        const categoryNumberB = parseInt(b.slice(-1), 10);
        return categoryNumberB - categoryNumberA;
    });

    const categoryContainers = sortedCategories.map((category) => {
        const categoryNumber = category.slice(-1);
        return (
            <div>
                <button
                    className="btn btn-primary button-collapse-header"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target={`#collapse-category-${category}`}
                    aria-expanded={activeCategories[category] ? 'true' : 'false'}
                    aria-controls={`collapse-category-${category}`}
                    onClick={() => toggleCategory(category)}
                >
                    Kategorie {categoryNumber}. ročníků
                </button>
                <div
                    className={`collapse toggle-content ${activeCategories[category] ? 'show' : ''}`}
                    id={`collapse-category-${category}`}
                >
                    <CategoryResults submits={resultsData.submits[category]} tasks={resultsData.tasks[category]} />
                </div>
            </div>
        );
    });

    return <div>{categoryContainers}</div>;
}
