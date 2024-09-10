import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import './style.scss';
import { TranslatorProvider, useTranslator } from './resultsTranslator';

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
    const { translate } = useTranslator();
    const [activeSeries, setActiveSeries] = useState<{ [key: string]: boolean }>({});
    const [sortColumn, setSortColumn] = useState<string | null>('Category Rank');
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
                } else if (sortColumn === 'Category Rank') {
                    return sortDirection === 'asc' ? a.rank[0] - b.rank[0] : b.rank[0] - a.rank[0];
                }
                return 0;
            });
            return sorted;
        }
        return submits;
    }, [submits, sortColumn, sortDirection]);

    // const head = [];
    // for (const series in tasks) {
    //     const tasksInSeries = tasks[series];
    //     const active = activeSeries[series] || false;
    //     if (active) {
    //         const subTasks = tasksInSeries.map((task, taskIndex) => {
    //             return (
    //                 <td className={`centered-cell ${taskIndex === 0 ? 'border-left' : ''} ${taskIndex === tasksInSeries.length - 1 ? 'border-right' : ''}`}>
    //                     <span className="task-label-header">{task.label}</span> <br></br>
    //                     <span data-label={task.label} className="points points-ok">{task.points}</span>
    //                 </td>
    //             );
    //         });
    //         head.push(<>{subTasks}</>);
    //     }
    //     head.push(
    //         <th
    //             onClick={() => {
    //                 const newActiveSeries = { [series]: !active };
    //                 for (const s in activeSeries) {
    //                     if (s !== series) {
    //                         newActiveSeries[s] = false;
    //                     }
    //                 }
    //                 setActiveSeries(newActiveSeries);
    //             }}
    //             className="centered-cell clickable-header"
    //         >
    //             s{series} <br></br> {active ? <span className="inactive-arrow"><>&#8594;</></span> : <span className="inactive-arrow"><>&#8592;</></span>}
    //         </th>
    //     );
    // };
    // head.push(
    //     <th className="centered-cell">
    //         Total<br/>Points
    //     </th>
    // )

    const [hoveredColumn, setHoveredColumn] = useState(null);

    const handleMouseEnter = (columnName: string) => {
        setHoveredColumn(columnName);
    };

    const handleMouseLeave = () => {
        setHoveredColumn(null);
    };

    return (
        <table className="table table-hover contest-results table-sm">
            <thead>
                <tr>
                    <th className="centered-cell">
                        {translate('categoryRank')}
                    </th>
                    <th className="centered-cell">
                        {translate('name')}
                    </th>
                    <th className="centered-cell">
                        {translate('school')}
                    </th>
                    {Object.entries(tasks).map(([series, tasksInSeries]) => {
                        return (
                            <React.Fragment key={`series-header-${series}`}>
                                {activeSeries[series] && tasksInSeries.map((task, index) => (
                                    <th 
                                        key={`task-header-${series}-${index}`}
                                        className={`centered-cell align-middle ${index === 0 ? 'border-left' : ''} ${index === tasksInSeries.length - 1 ? 'border-right' : ''}`}
                                    >
                                        <span className="task-label-header">{task.label}</span>
                                    </th>
                                ))}
                                <th
                                    onClick={() => {
                                        const newActiveSeries = { [series]: !activeSeries[series] };
                                        for (const s in activeSeries) {
                                            if (s !== series) {
                                                newActiveSeries[s] = false;
                                            }
                                        }
                                        setActiveSeries(newActiveSeries);
                                    }}
                                    className="centered-cell clickable-header"
                                >
                                    s{series} <br /> 
                                    {activeSeries[series] ? <span className="inactive-arrow">→</span> : <span className="inactive-arrow">←</span>}
                                </th>
                            </React.Fragment>
                        );
                    })}
                    <th 
                        className={`centered-cell clickable-header ${hoveredColumn === 'Category Rank' || hoveredColumn === 'Total Points' ? 'hovered' : ''}`}
                        onClick={() => toggleSort('Category Rank')}
                        onMouseEnter={() => handleMouseEnter('Category Rank')}
                        onMouseLeave={handleMouseLeave}
                    >
                        {translate('totalPoints')}
                        {sortColumn === 'Category Rank' ? (
                            <span style={{ color: 'black' }}>
                                {sortDirection === 'asc' ? '↓' : '↑'}
                            </span>
                        ) : (
                            <span className="inactive-arrow">
                                ↓
                            </span>
                        )}
                    </th>
                </tr>
                <tr className="max-points-row">
                    <th></th>
                    <th>{translate('maxNumPointsHeader')}</th>
                    <th></th>
                    {Object.entries(tasks).map(([series, tasksInSeries]) => {
                        const seriesMaxPoints = tasksInSeries.reduce((sum, task) => sum + (typeof task.points === 'number' ? task.points : 0), 0);
                        return (
                            <React.Fragment key={`max-points-${series}`}>
                                {activeSeries[series] && tasksInSeries.map((task, index) => (
                                    <th 
                                        key={`max-points-${series}-${index}`}
                                        className={`centered-cell ${index === 0 ? 'border-left' : ''} ${index === tasksInSeries.length - 1 ? 'border-right' : ''}`}
                                    >
                                        <span data-label={task.label} className="points points-ok">{task.points}</span>
                                    </th>
                                ))}
                                <th className="centered-cell">
                                    {seriesMaxPoints}
                                </th>
                            </React.Fragment>
                        );
                    })}
                    <th className="centered-cell">
                        {Object.values(tasks).reduce((totalSum, tasksInSeries) => 
                            totalSum + tasksInSeries.reduce((seriesSum, task) => seriesSum + (typeof task.points === 'number' ? task.points : 0), 0), 0
                        )}
                    </th>
                </tr>
            </thead>
            <tbody>
                {sortedSubmits.map((contestant, index) => {
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
                        <tr key={`contestant-${contestant.contestant.contestantId}-${index}`}>
                            <td className="centered-cell">{contestant.rank[0]}</td>
                            <td>{contestant.contestant.name}</td>
                            <td>{contestant.contestant.school}</td>
                            {seriesContainers}
                            <td className="centered-cell">
                                <strong>{contestant.sum}</strong>
                            </td>
                        </tr>
                    );
                })}
            </tbody>
        </table>
    );

    // return (
    //     <table className="table table-hover contest-results table-sm">
    //         <thead>
    //             <tr>
    //                 <th
    //                     className={`centered-cell clickable-header ${hoveredColumn === 'Category Rank' || hoveredColumn === 'Total Points' ? 'hovered' : ''}`}
    //                     onClick={() => toggleSort('Category Rank')}
    //                     onMouseEnter={() => handleMouseEnter('Total Points')}
    //                     onMouseLeave={handleMouseLeave}
    //                 >
    //                     {translate('categoryRank')}
    //                     {sortColumn === 'Category Rank' ? (
    //                         <span style={{ color: 'black' }}>
    //                             {sortDirection === 'asc' ? '↓' : '↑'}
    //                         </span>
    //                     ) : (
    //                         <span className="inactive-arrow">
    //                             ↓
    //                         </span>
    //                     )}
    //                 </th>
    //                 <th className="centered-cell clickable-header" onClick={() => toggleSort('Name')}>
    //                     {translate('name')}
    //                     {sortColumn === 'Name' ? (
    //                         <span style={{ color: 'black' }}>
    //                             {sortDirection === 'asc' ? '↓' : '↑'}
    //                         </span>
    //                     ) : (
    //                         <span className="inactive-arrow">
    //                             ↓
    //                         </span>
    //                     )}
    //                 </th>
    //                 <th
    //                     onClick={() => toggleSort('School')}
    //                     className="centered-cell clickable-header"
    //                 >
    //                     {translate('school')}
    //                     {sortColumn === 'School' ? (
    //                         <span style={{ color: 'black' }}>
    //                             {sortDirection === 'asc' ? '↓' : '↑'}
    //                         </span>
    //                     ) : (
    //                         <span className="inactive-arrow">
    //                             ↓
    //                         </span>
    //                     )}
    //                 </th>
    //                 {head}
    //                 <th 
    //                     className={`centered-cell clickable-header ${hoveredColumn === 'Category Rank' || hoveredColumn === 'Total Points' ? 'hovered' : ''}`}
    //                     onClick={() => toggleSort('Category Rank')}
    //                     onMouseEnter={() => handleMouseEnter('Category Rank')}
    //                     onMouseLeave={handleMouseLeave}
    //                 >
    //                     {translate('totalPoints')}
    //                     {sortColumn === 'Category Rank' ? (
    //                         <span style={{ color: 'black' }}>
    //                             {sortDirection === 'asc' ? '↓' : '↑'}
    //                         </span>
    //                     ) : (
    //                         <span className="inactive-arrow">
    //                             ↓
    //                         </span>
    //                     )}
    //                 </th>
    //             </tr>
    //         </thead>
    //         <tbody>
    //             {sortedSubmits.map((contestant) => {
    //                 const seriesContainers = [];
    //                 for (const series in tasks) {
    //                     const tasksInSeries = tasks[series];
    //                     seriesContainers.push(
    //                         <SeriesResults
    //                             series={series}
    //                             key={series}
    //                             tasks={tasksInSeries}
    //                             contestant={contestant}
    //                             show={activeSeries[series] || false}
    //                         />
    //                     );
    //                 }
    //                 return (
    //                     <tr>
    //                         <td className="centered-cell">{contestant.rank[0]}</td>
    //                         <td>{contestant.contestant.name}</td>
    //                         <td>{contestant.contestant.school}</td>
    //                         {seriesContainers}
    //                         <td className="centered-cell">
    //                             <strong>{contestant.sum}</strong></td>
    //                     </tr>
    //                 );
    //             })}
    //         </tbody>
    //     </table>
    // );
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
    const { translate } = useTranslator();
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
                    {translate('categoryLabel', { categoryNumber })}
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

document.addEventListener('DOMContentLoaded', () => {
    const element = document.getElementById('contest-results');
    if (element) {
        const data = JSON.parse(element.getAttribute('data-data'));
        ReactDOM.render(
            <TranslatorProvider>
                <Results resultsData={data} />
            </TranslatorProvider>,
            element
        );
    }
});
