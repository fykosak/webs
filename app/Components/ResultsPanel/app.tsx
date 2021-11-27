import React, {memo, useContext, useEffect, useMemo, useState} from "react";
import {createPortal} from "react-dom";
import {useData} from "../ApiResults/use-data";
import {useTeamPoints} from "../ApiResults/use-team-points";
import {useToggle} from "../../../js/use-toggle";
import {CountdownPortalContext, LangContext} from "./main";
import {Team} from "../ApiResults/team-interface";
import {DataInterface, isDataInterfaceVisible} from "../ApiResults/data-interface";

export const App: React.FC<{url: string, teams: Team[], results: DataInterface}> = memo(({url, teams, results}) => {
  const data = useData(url, results);
  const showLive = data && new Date(data.times.gameEnd).getTime() > new Date().getTime();
  const lang = useContext(LangContext);
  return <>
    {showLive && <p>{lang === 'cs' ? "Výsledky jsou živě ze soutěže" : "Results are live from the competition"}</p>}
    {isDataInterfaceVisible(data) ? <ForVisibleResults data={data} teams={teams} /> : <ForHiddenResults data={data} />}
    <CountDownPortal results={results} />
  </>
});

const CountDownPortal: React.FC<{results: DataInterface}> = memo(({results}) => {
  const element = useContext(CountdownPortalContext);

  const [, stateTrigger] = useState<object>({});
  useEffect(() => {
    const interval = setInterval(() => stateTrigger({}), 1000);
    return () => clearInterval(interval);
  }, []);

  let diff = new Date(results.times.gameEnd).getTime() - new Date().getTime();
  const after = diff < 0;

  const hours = Math.floor(diff / (1000 * 60 * 60));
  diff -= hours * (1000 * 60 * 60);
  const minutes = Math.floor(diff / (1000 * 60));
  diff -= minutes * (1000 * 60);
  const seconds = Math.floor(diff / 1000);

  return createPortal(after || <span>
    {String(hours).padStart(2, "0")}:
    {String(minutes).padStart(2, "0")}:
    {String(seconds).padStart(2, "0")}
  </span>, element);
});

export const ForVisibleResults: React.FC<{data: DataInterface<true>, teams: Team[]}> = ({data, teams}) => {
  const points = useTeamPoints(data);
  const [showFull, toggleShowFull] = useToggle();
  const lang = useContext(LangContext);
  const mappedTeams = useMemo(() => Object.fromEntries(teams.map(t => [t.teamId, t])), [teams]);

  return <>
    <div className="row strips">
      {["A", "B", "C", "O"].map((c,i) =>
        <div className="col-md-3" key={i}>
          <CategoryColumn category={c} points={points} showFull={showFull} mappedTeams={mappedTeams} />
        </div>
      )}
    </div>
    {showFull && false && <div className="row">
        <CategoryColumn category={"O"} points={points} showFull={true} mappedTeams={mappedTeams} />
    </div> }
    <button onClick={toggleShowFull} className="btn btn-panel-action">{showFull ? (
      lang === 'cs' ? "Skrýt" : "Hide"
    ) : (
      lang === 'cs' ? "Zobrazit všechny týmy" : "Show all teams"
    )}</button>
  </>;
}

export const ForHiddenResults: React.FC<{data: DataInterface}> = memo(({data}) => {
  const lang = useContext(LangContext);
  return <div className={"hidden-results"}>
    {lang === 'cs' ? "Výsledky soutěže jsou 20 minut před koncem soutěže skryté." : "Competition results are hidden 20 minutes before the end of the competition."}
  </div>;
});

const CATEGORY_NAMES = {
  "A": "A",
  "B": "B",
  "C": "C",
  "O": "Open",
}

const CategoryColumn: React.FC<{
  category: string,
  points: ReturnType<typeof useTeamPoints> | null,
  showFull: boolean,
  mappedTeams: Record<number, Team>,
}>
  = memo(({category, points, showFull, mappedTeams}) => {
  const lang = useContext(LangContext);

  const sorted = useMemo(() => points
    ?.filter(p => p.team.category === category)
    .sort((a, b) => {
        if (a.points === b.points) {
          if (a.points === 0) {
            return a.team.teamId - b.team.teamId;
          } else {
            return a.lastSubmit > b.lastSubmit ? 1 : -1;
          }
        } else {
          return b.points - a.points;
        }
      }
    ).filter((_, i) => showFull || i < 10), [points, showFull]);

  return <>
    <div className="category-title">
      {lang === 'cs' ?
        <>Kategorie {CATEGORY_NAMES[category as keyof typeof CATEGORY_NAMES]}</>:
        <>{CATEGORY_NAMES[category as keyof typeof CATEGORY_NAMES]} category</>
      }
      </div>
    <table>
      <tbody>
      {sorted?.map((p, i) => <tr key={i} className={p.points ? "" : "zero-points"}>
        <td>{p.points ? `${i + 1}.` : '-'}</td>
        <td>
          <div className="team-name">{mappedTeams[p.team.teamId]?.name ?? p.team.name}</div>
          <div className="flags">
            {[...new Set(mappedTeams[p.team.teamId]?.participants.map(p => p.countryIso))].filter(iso => iso !== "ZZ").map(iso =>
              <span key={iso} className={`flag-icon flag-icon-${iso.toLowerCase()}`} />
            )}
          </div>
        </td>
        <td>{p.points}</td>
      </tr> )}
      </tbody>
    </table>
  </>;
});
