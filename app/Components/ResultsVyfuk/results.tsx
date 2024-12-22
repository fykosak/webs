import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import './style.scss';
import { TranslatorProvider, useTranslator } from './resultsTranslator'; //Pokud bude natvrdo česky napsaný, tak není potřeba

interface Contestant {
    contestant: {
        contestantId: number; name: string; school: string;
    };
    rank: [number, number]; // in case of sharing a rank among multiple contestants, the first and last rank are stored
    submits: {
        [key: number]: number;
    };
    totalRank: [number, number];
    sunOneToThree: number;
    sumFourToSix: number;
    sum: number;
    category: number;
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

function CategoryResults({ submits, tasks, isAllCategories = false }: { submits: Submits, tasks: Tasks, isAllCategories?: boolean }) {
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

    // calculate the sumOneToThree and sumFourToSix for each contestant
    for (const contestant of submits) {
        let sumOneToThree = 0;
        let sumFourToSix = 0;
        for (const series in tasks) {
            const tasksInSeries = tasks[series];
            let sum = 0;
            for (const task of tasksInSeries) {
                if (contestant.submits.hasOwnProperty(task.taskId)) {
                    if (contestant.submits[task.taskId] === null) {
                        // sum = null;
                        // break;
                        sum += 0;
                    } else {
                        sum += contestant.submits[task.taskId];
                    }
                }
            }
            if (sum === null) {
                sumOneToThree = null;
                sumFourToSix = null;
                break;
            } else if (parseInt(series) <= 3) {
                sumOneToThree += sum;
            } else {
                sumFourToSix += sum;
            }
        }
        contestant.sunOneToThree = sumOneToThree;
        contestant.sumFourToSix = sumFourToSix;
        contestant.sum = sumOneToThree === null || sumFourToSix === null ? null : sumOneToThree + sumFourToSix;
    }


    // if isAllCategories, calculate total rank, i.e. sort contestants by their points sum
    if (isAllCategories) {
        // create a copy of the array
        const tempSubmits = [...submits];

        tempSubmits.sort((a, b) => {
            return b.sum - a.sum;
        });

        let currentRank = 0;
        let sharedRankStart = 0;

        tempSubmits.forEach((contestant, index) => {
            if (index === 0 || contestant.sum !== tempSubmits[index - 1].sum) {
                if (index > 0 && sharedRankStart < index - 1) {
                    // Update shared ranks for previous group
                    for (let i = sharedRankStart; i < index; i++) {
                        tempSubmits[i].totalRank = [sharedRankStart + 1, index];
                    }
                }
                currentRank = index + 1;
                sharedRankStart = index;
            }
            contestant.totalRank = [currentRank, currentRank];
        });

        // Handle the last group
        if (sharedRankStart < tempSubmits.length - 1) {
            for (let i = sharedRankStart; i < tempSubmits.length; i++) {
                tempSubmits[i].totalRank = [sharedRankStart + 1, tempSubmits.length];
            }
        }
    }

    const sortedSubmits = React.useMemo(() => {
        if (sortColumn) {
            const sorted = [...submits];
            sorted.sort((a, b) => {
                if (sortColumn === 'Name') {
                    const lastNameA = a.contestant.name.split(' ').pop();
                    const lastNameB = b.contestant.name.split(' ').pop();
                    return sortDirection === 'asc' ? lastNameA.localeCompare(lastNameB) : lastNameB.localeCompare(lastNameA);
                } else if (sortColumn === 'School') {
                    const schoolA = a.contestant.school || '';
                    const schoolB = b.contestant.school || '';
                    return sortDirection === 'asc' ? schoolA.localeCompare(schoolB) : schoolB.localeCompare(schoolA);
                } else if (sortColumn === 'Category Rank') {
                    if (isAllCategories) {
                        return sortDirection === 'asc' ? a.totalRank[0] - b.totalRank[0] : b.totalRank[0] - a.totalRank[0];
                    }
                    return sortDirection === 'asc' ? a.rank[0] - b.rank[0] : b.rank[0] - a.rank[0];
                } else if (sortColumn === 's1-3') {
                    return sortDirection === 'asc' ? b.sunOneToThree - a.sunOneToThree : a.sunOneToThree - b.sunOneToThree;
                } else if (sortColumn === 's4-6') {
                    return sortDirection === 'asc' ? b.sumFourToSix - a.sumFourToSix : a.sumFourToSix - b.sumFourToSix;
                } else if (sortColumn === 'Category') {
                    return sortDirection === 'asc' ? a.category - b.category : b.category - a.category;
                }
                return 0;
            });
            return sorted;
        }
        return submits;
    }, [submits, sortColumn, sortDirection]);

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
                    <th
                        className={`centered-cell clickable-header ${hoveredColumn === 'Category Rank' ? 'hovered' : ''}`}
                        onClick={() => toggleSort('Category Rank')}
                        onMouseEnter={() => handleMouseEnter('Category Rank')}
                        onMouseLeave={handleMouseLeave}
                    >
                        {isAllCategories ? translate('totalRank') : translate('categoryRank')}&nbsp;
                        {sortColumn === 'Category Rank' ? (
                            <span style={{ color: 'black' }}>
                                {sortDirection === 'asc' ? '↑' : '↓'}
                            </span>
                        ) : (
                            <span className="inactive-arrow">
                                ↓
                            </span>
                        )}
                    </th>
                    {isAllCategories ? (<th
                        className={`centered-cell align-middle clickable-header ${hoveredColumn === 'Category' ? 'hovered' : ''}`}
                        onClick={() => toggleSort('Category')}
                        onMouseEnter={() => handleMouseEnter('Category')}
                        onMouseLeave={handleMouseLeave}
                    >
                        {translate('category')}<br />
                        {sortColumn === 'Category' ? (
                            <span style={{ color: 'black' }}>
                                {sortDirection === 'asc' ? '↑' : '↓'}
                            </span>
                        ) : (
                            <span className="inactive-arrow">
                                ↓
                            </span>
                        )}
                    </th>) : null}
                    <th
                        className={`centered-cell align-middle clickable-header ${hoveredColumn === 'Name' ? 'hovered' : ''}`}
                        onClick={() => toggleSort('Name')}
                        onMouseEnter={() => handleMouseEnter('Name')}
                        onMouseLeave={handleMouseLeave}
                    >
                        {translate('name')}<br />
                        {sortColumn === 'Name' ? (
                            <span style={{ color: 'black' }}>
                                {sortDirection === 'asc' ? '↑' : '↓'}
                            </span>
                        ) : (
                            <span className="inactive-arrow">
                                ↓
                            </span>
                        )}
                    </th>
                    <th
                        className={`centered-cell align-middle clickable-header ${hoveredColumn === 'School' ? 'hovered' : ''}`}
                        onClick={() => toggleSort('School')}
                        onMouseEnter={() => handleMouseEnter('School')}
                        onMouseLeave={handleMouseLeave}
                    >
                        {translate('school')}<br />
                        {sortColumn === 'School' ? (
                            <span style={{ color: 'black' }}>
                                {sortDirection === 'asc' ? '↑' : '↓'}
                            </span>
                        ) : (
                            <span className="inactive-arrow">
                                ↓
                            </span>
                        )}
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
                    {isAllCategories ? <th></th> : null}
                    <th colSpan={2}>{translate('maxNumPointsHeader')}</th>
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
                            <td className="centered-cell">{isAllCategories ? (contestant.totalRank[0] === contestant.totalRank[1] ? contestant.totalRank[0] : `${contestant.totalRank[0]}-${contestant.totalRank[1]}`) : (contestant.rank[0] === contestant.rank[1] ? contestant.rank[0] : `${contestant.rank[0]}-${contestant.rank[1]}`)}</td>
                            {isAllCategories ? <td className="centered-cell">{contestant.category}</td> : null}
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

    // assign contestants to categories, it is the last character of the key of the submits object
    // for category, values is submits -> for contestant in values -> contestant.category = key
    for (const category in resultsData.submits) {
        for (const contestant of resultsData.submits[category]) {
            contestant.category = parseInt(category.slice(-1), 10);
        }
    }

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
                    className={`collapse toggle-content scrollable-container ${activeCategories[category] ? 'show' : ''}`}
                    id={`collapse-category-${category}`}
                >
                    <CategoryResults submits={resultsData.submits[category]} tasks={resultsData.tasks[category]} />
                </div>
            </div>
        );
    });

    // append total results, i.e. results for all categories
    const allCategories = Object.values(resultsData.submits).reduce((allCategories, categorySubmits) => {
        return allCategories.concat(categorySubmits);
    }, []);

    const allTasks = resultsData.tasks[sortedCategories[0]];

    categoryContainers.push(
        <div>
            <button
                className="btn btn-primary button-collapse-header"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapse-all-categories"
                aria-expanded={activeCategories.all ? 'true' : 'false'}
                aria-controls="collapse-all-categories"
                onClick={() => toggleCategory('all')}
            >
                {translate('allCategories')}
            </button>
            <div
                className={`collapse toggle-content scrollable-container ${activeCategories.all ? 'show' : ''}`}
                id="collapse-all-categories"
            >
                <CategoryResults submits={allCategories} tasks={allTasks} isAllCategories={true} />
            </div>
        </div>
    );



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
