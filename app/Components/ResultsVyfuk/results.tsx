import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import './style.scss';
import { TranslatorProvider, useTranslator } from './resultsTranslator'; //Pokud bude natvrdo česky napsaný, tak není potřeba
import { s } from '@fullcalendar/core/internal-common';

interface Contestant {
    contestant: {
        contestantId: number; name: string; school: string;
    };
    rank: [number, number]; // in case of sharing a rank among multiple contestants, the first and last rank are stored
    submits: {
        [key: number]: number;
    };
    totalRank: [number, number];
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
    },
    series: number[]
}

function CategoryResults({ submits, tasks, isAllCategories = false, serie = 0 }: { submits: Submits, tasks: Tasks, isAllCategories?: boolean, serie?: number }) {
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

    // calculate the sumSum for each contestant
    for (const contestant of submits) {
        let sumAll = 0;
        for (const series in tasks) {
            if (serie > 0 && serie != parseInt(series))
                continue
            const tasksInSeries = tasks[series];
            let sum = 0;
            for (const task of tasksInSeries) {
                if (contestant.submits.hasOwnProperty(task.taskId)) {
                    if (contestant.submits[task.taskId] !== null) {
                        sum += contestant.submits[task.taskId];
                    }
                }
            }
            sumAll += sum;
        }
        contestant.sum = sumAll;
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
                } else if (sortColumn === 'Category') {
                    return sortDirection === 'asc' ? a.category - b.category : b.category - a.category;
                }
                return 0;
            });
            return sorted;
        }
        return submits;
    }, [submits, sortColumn, sortDirection, serie]);

    return (
        <table className="table table-hover contest-results table-sm">
            <thead>
                <tr>
                    <th
                        className={`centered-cell clickable-header`}
                        onClick={() => toggleSort('Category Rank')}
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
                        className={`centered-cell align-middle clickable-header`}
                        onClick={() => toggleSort('Category')}
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
                        className={`centered-cell align-middle clickable-header`}
                        onClick={() => toggleSort('Name')}
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
                        className={`centered-cell align-middle clickable-header`}
                        onClick={() => toggleSort('School')}
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
                        if (serie > 0 && serie != parseInt(series))
                            return (null);
                        return (
                            <React.Fragment key={`series-header-${series}`}>
                                {(activeSeries[series] || serie > 0) && tasksInSeries.map((task, index) => (
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
                                    {serie > 0 ? null : (activeSeries[series] ? <span className="inactive-arrow">→</span> : <span className="inactive-arrow">←</span>)}
                                </th>
                            </React.Fragment>
                        );
                    })}
                    {serie == 0 ?
                        <th
                            className={`centered-cell clickable-header`}
                            onClick={() => toggleSort('Category Rank')}
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
                        </th> : null
                    }
                </tr>
                <tr className="max-points-row">
                    <th></th>
                    {isAllCategories ? <th></th> : null}
                    <th colSpan={2}>{translate('maxNumPointsHeader')}</th>
                    {Object.entries(tasks).map(([series, tasksInSeries]) => {
                        if (serie > 0 && serie != parseInt(series))
                            return (null);
                        const seriesMaxPoints = tasksInSeries.reduce((sum, task) => sum + (typeof task.points === 'number' ? task.points : 0), 0);
                        return (
                            <React.Fragment key={`max-points-${series}`}>
                                {(activeSeries[series] || serie > 0) && tasksInSeries.map((task, index) => (
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
                    {serie == 0 ?
                        <th className="centered-cell">
                            {Object.values(tasks).reduce((totalSum, tasksInSeries) =>
                                totalSum + tasksInSeries.reduce((seriesSum, task) => seriesSum + (typeof task.points === 'number' ? task.points : 0), 0), 0
                            )}
                        </th> : null
                    }
                </tr>
            </thead>
            <tbody>
                {sortedSubmits.map((contestant, index) => {
                    const seriesContainers = [];
                    let showContestant = false;
                    for (const series in tasks) {
                        if (serie > 0 && serie != parseInt(series))
                            continue
                        const tasksInSeries = tasks[series];
                        let [showPerSeriesContestant, element]: any = SeriesResults(
                            {
                                series: series,
                                tasks: tasksInSeries,
                                contestant: contestant,
                                show: activeSeries[series] || serie > 0
                            });
                        showContestant ||= showPerSeriesContestant;
                        seriesContainers.push(element);
                    }
                    return showContestant ? (
                        <tr key={`contestant-${contestant.contestant.contestantId}-${index}`}>
                            <td className="centered-cell">{isAllCategories ? (contestant.totalRank[0] === contestant.totalRank[1] ? contestant.totalRank[0] : `${contestant.totalRank[0]}-${contestant.totalRank[1]}`) : (contestant.rank[0] === contestant.rank[1] ? contestant.rank[0] : `${contestant.rank[0]}-${contestant.rank[1]}`)}</td>
                            {isAllCategories ? <td className="centered-cell">{contestant.category}</td> : null}
                            <td>{contestant.contestant.name}</td>
                            <td>{contestant.contestant.school}</td>
                            {seriesContainers}
                            {
                                serie == 0 ?
                                    <td className="centered-cell">
                                        <strong>{contestant.sum}</strong>
                                    </td> : null
                            }
                        </tr>
                    ) : null;
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
    let subTasks: JSX.Element[] = [];
    let showContestant = false;
    subTasks = tasks.map((task, index) => {
        let badge;
        if (contestant.submits.hasOwnProperty(task.taskId)) {
            showContestant = true;
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
    return ([showContestant,
        <>
            {show && subTasks}
            <td data-series={series} className="centered-cell">
                <strong>{sum}</strong>
            </td>
        </>]
    );
}

function OLDResults({ resultsData, series }: Props) {
    const { translate } = useTranslator();
    const [activeCategories, setActiveCategories] = useState<{ [key: string]: boolean }>({});
    const [selectedSerie, setSelectedSerie] = useState(0);

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

    let categoryContainers = sortedCategories.map((category) => {
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
                    <CategoryResults submits={resultsData.submits[category]} tasks={resultsData.tasks[category]} serie={selectedSerie} />
                </div>
            </div>
        );
    });
    let serieSelection = series.map((number) => {
        if (number < 7)
            return (
                <button
                    className={`btn ${selectedSerie == number ? 'btn-primary' : ''}`}
                    onClick={() => setSelectedSerie(number)}
                >
                    {number}. série
                </button>
            )
        else
            return (
                <button
                    onClick={() => setSelectedSerie(number)}
                    className={`btn ${selectedSerie == number ? 'btn-primary' : ''}`}
                >
                    {number - 7}. prázdninová série
                </button>
            )
    })
    serieSelection.push(
        <button
            onClick={() => setSelectedSerie(0)}
            className={`btn ${selectedSerie == 0 ? 'btn-primary' : ''}`}
        >
            Celkové výsledky
        </button>
    )
    categoryContainers = [
        <div
            className='series-select'
        >
            {serieSelection}
        </div>,
        ...categoryContainers]

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
                <CategoryResults submits={allCategories} tasks={allTasks} isAllCategories={true} serie={selectedSerie} />
            </div>
        </div>
    );



    return <div>{categoryContainers}</div>;
}

function Results({ data, series }: { data: { submits: { [key: string]: Submits; }, tasks: { [key: string]: Tasks; } }, series: number[] }) {
    const [selectedSerie, setSelectedSerie] = useState(0);
    const sortedCategories = Object.keys(data.submits).sort((a, b) => {
        return parseInt(b.slice(-1)) - parseInt(a.slice(-1));
    });
    const sortedSeries = series.sort((a, b) => a - b);

    // category selection
    let serieSelection = sortedSeries.map((number) => {
        if (number < 7)
            return (
                <button
                    className={`btn ${selectedSerie == number ? 'btn-primary' : ''}`}
                    onClick={() => setSelectedSerie(number)}
                >
                    {number}. série
                </button>
            )
        else
            return (
                <button
                    onClick={() => setSelectedSerie(number)}
                    className={`btn ${selectedSerie == number ? 'btn-primary' : ''}`}
                >
                    {number - 7}. prázdninová série
                </button>
            )
    })
    serieSelection.push(
        <button
            onClick={() => setSelectedSerie(0)}
            className={`btn ${selectedSerie == 0 ? 'btn-primary' : ''}`}
        >
            Celkové výsledky
        </button>
    )
    let categoryContainers = [
        <div
            className='series-select'
        >
            {serieSelection}
        </div>]
    for (const category of sortedCategories) {
        let tableDef: any = React.useMemo(() => {
            let columns: ColumnDef[] = [];
            let taskLockup: { [key: number]: number } = {};
            columns.push({ colKey: "rank", label: "#", sortable: false, numerical: false });
            columns.push({ colKey: "name", label: "Jméno", sortable: false, numerical: false });
            columns.push({ colKey: "school", label: "Škola", sortable: false, numerical: false });
            for (let serie in data.tasks[category]) {
                let tasks = data.tasks[category][serie].sort((a, b) => {
                    return a.label.localeCompare(b.label);
                });
                for (let [key, t] of tasks.entries()) {
                    taskLockup[t.taskId] = columns.length;
                    columns.push({ colKey: "s" + serie + "." + t.label, label: t.label + "(" + t.points + "b.)", sortable: true, numerical: true });
                }
                columns.push({ colKey: "s" + serie, label: "s" + serie, sortable: true, numerical: true });
            }
            columns.push({ colKey: "sum", label: "Celkem\nbodů", sortable: true, numerical: true });

            let outData = []
            for (let contestant of data.submits[category]) {
                let row: any = {};
                row["name"] = contestant.contestant.name;
                row["school"] = contestant.contestant.school;
                row["rank"] = contestant.rank[0] == contestant.rank[1] ? String(contestant.rank[0]) + "." : String(contestant.rank[0]) + ".-" + String(contestant.rank[1]) + ".";

                let totalSum = 0;
                for (let serie in data.tasks[category]) {
                    let tasks = data.tasks[category][serie].sort((a, b) => {
                        return a.label.localeCompare(b.label);
                    });
                    let serieSum = 0;
                    for (let [key, t] of tasks.entries()) {
                        if (contestant.submits.hasOwnProperty(t.taskId) && typeof contestant.submits[t.taskId] == 'number') {
                            row["s" + serie + "." + t.label] = contestant.submits[t.taskId];
                            serieSum += contestant.submits[t.taskId];
                        } else {
                            row["s" + serie + "." + t.label] = '-';
                        }
                    }
                    row["s" + serie] = serieSum;
                    totalSum += serieSum;
                }
                row["sum"] = totalSum;
                outData.push(row);
            }
            return { columns: columns, data: outData };
        }, [data, series]);
        let tableManager: any = {};
        [tableManager.sortColum, tableManager.setSortColum] = useState("sum");
        [tableManager.sortAsc, tableManager.setSortAsc] = useState(false);
        [tableManager.hideColum, tableManager.setHideColum] = useState([]);
        tableDef.tableManager = tableManager;
        categoryContainers.push(
            <SortTable tableDef={tableDef} />
        )
    }


    return (<div>{categoryContainers}</div>)
}

type TableManager = {
    sortColum: string,
    setSortColum: React.Dispatch<React.SetStateAction<string>>,
    sortAsc: boolean,
    setSortAsc: React.Dispatch<React.SetStateAction<boolean>>,
    hideColum: { [key: string]: boolean }
    setHideColum: React.Dispatch<React.SetStateAction<any>>
};
type ColumnDef = { colKey: string, label: string | null, sortable: boolean, numerical: boolean };

type TableDef = { columns: Array<ColumnDef>, data: { [key: string]: any }[], tableManager: TableManager };

function SortColumn({ colKey, label = null, tableManager }: { colKey: string, label?: string | null, tableManager: TableManager }) {
    if (tableManager.hideColum.hasOwnProperty(colKey) && tableManager.hideColum[colKey] == true)
        return null;
    let element = tableManager.sortColum == colKey ? (
        <span style={{ color: 'black' }}>
            {tableManager.sortAsc ? '↓' : '↑'}
        </span>
    ) : (
        <span className="inactive-arrow">
            ↕
        </span>
    );
    return (
        <th
            className={`centered-cell clickable-header`}
            onClick={() => {
                if (colKey == tableManager.sortColum) {
                    tableManager.setSortAsc(!tableManager.sortAsc);
                } else {
                    tableManager.setSortColum(colKey);
                    tableManager.setSortAsc(false);
                }
            }}
        >
            {label == null ? colKey : label}&nbsp;
            {element}
        </th>
    )
}

function Column({ colKey, label = null, tableManager }: { colKey: string, label?: string | null, tableManager: TableManager }) {
    if (tableManager.hideColum.hasOwnProperty(colKey) && tableManager.hideColum[colKey] == true)
        return null;
    return (
        <th
            className={`centered-cell`}
        >
            {label == null ? colKey : label}
        </th>
    )
}

function SortTable({ tableDef }: { tableDef: TableDef }) {
    let [onlySome, setOnlySome] = useState(true);
    let tableHeader = tableDef.columns.map((columnDef) => {
        if (tableDef.tableManager.hideColum[columnDef.colKey])
            return null;
        return (columnDef.sortable ?
            <SortColumn colKey={columnDef.colKey} label={columnDef.label} tableManager={tableDef.tableManager}></SortColumn> :
            <Column colKey={columnDef.colKey} label={columnDef.label} tableManager={tableDef.tableManager}></Column>
        )
    })
    let data = tableDef.data;
    data.sort((a, b) => {
        let result: any = 0;
        if (
            tableDef.columns.reduce((returning, col) => {
                return returning || (col.colKey == tableDef.tableManager.sortColum && col.numerical)
            }, false)
        ) {
            result = ((typeof a[tableDef.tableManager.sortColum] == "number" ? a[tableDef.tableManager.sortColum] : 0)
                - (typeof b[tableDef.tableManager.sortColum] == "number" ? b[tableDef.tableManager.sortColum] : 0));
        } else {
            result = String(a[tableDef.tableManager.sortColum]).localeCompare(b[tableDef.tableManager.sortColum]);
        }
        if (!tableDef.tableManager.sortAsc) {
            result = -result;
        }
        return result;
    })
    if (onlySome) {
        data = data.slice(0, 10);
    }
    let tableBody: JSX.Element[] = [];
    console.log(data)
    for (let dat of data) {
        console.log(dat)
        let dataRow: JSX.Element[] = [];
        let show = false;
        for (let c of tableDef.columns) {
            if (!tableDef.tableManager.hideColum.hasOwnProperty(c.colKey) || tableDef.tableManager.hideColum[c.colKey]) {
                show ||= typeof dat[c.colKey] == 'number'
                dataRow.push(<td>{dat[c.colKey]}</td>)
            }
        }
        tableBody.push(<tr>{dataRow}</tr>)
    }


    return (<>
        <table className='table table-hover contest-results table-sm'>
            <thead><tr>
                {tableHeader}
            </tr></thead>
            <tbody>{tableBody}</tbody>
        </table>
        <button
            className="btn btn-primary button-collapse-header"
            type="button"
            onClick={() => setOnlySome(!onlySome)}
        >
            {onlySome ? 'Zobrazit všechny' : 'Zobrazit méně'}
        </button>
    </>);
    /*    return (
            <table className="table table-hover contest-results table-sm">
                <tbody>
                    {sortedSubmits.map((contestant, index) => {
                        const seriesContainers = [];
                        let showContestant = false;
                        for (const series in tasks) {
                            if (serie > 0 && serie != parseInt(series))
                                continue
                            const tasksInSeries = tasks[series];
                            let [showPerSeriesContestant, element]: any = SeriesResults(
                                {
                                    series: series,
                                    tasks: tasksInSeries,
                                    contestant: contestant,
                                    show: activeSeries[series] || serie > 0
                                });
                            showContestant ||= showPerSeriesContestant;
                            seriesContainers.push(element);
                        }
                        return showContestant ? (
                            <tr key={`contestant-${contestant.contestant.contestantId}-${index}`}>
                                <td className="centered-cell">{isAllCategories ? (contestant.totalRank[0] === contestant.totalRank[1] ? contestant.totalRank[0] : `${contestant.totalRank[0]}-${contestant.totalRank[1]}`) : (contestant.rank[0] === contestant.rank[1] ? contestant.rank[0] : `${contestant.rank[0]}-${contestant.rank[1]}`)}</td>
                                {isAllCategories ? <td className="centered-cell">{contestant.category}</td> : null}
                                <td>{contestant.contestant.name}</td>
                                <td>{contestant.contestant.school}</td>
                                {seriesContainers}
                                {
                                    serie == 0 ?
                                        <td className="centered-cell">
                                            <strong>{contestant.sum}</strong>
                                        </td> : null
                                }
                            </tr>
                        ) : null;
                    })}
                </tbody>
            </table>
        );*/
}

document.addEventListener('DOMContentLoaded', () => {
    const element = document.getElementById('contest-results');
    if (element) {
        const data = JSON.parse(element.getAttribute('data-data'));
        const series = JSON.parse(element.getAttribute('data-series'));
        ReactDOM.render(
            <Results data={data} series={series} />,
            element
        );
    }
});
