import {DataInterface, Team} from "./data-interface";
import {useMemo} from "react";
import {Simulate} from "react-dom/test-utils";

export const useTeamPoints = (data: DataInterface<true> | null): {team: Team<true>, points: number, lastSubmit: Date}[] | null => {
  return useMemo(() => {
    if (data) {
      return data.teams.map(team => {
        const teamSubmits = data.submits.filter(submit => submit.teamId === team.teamId);

        return {
          team,
          points: teamSubmits.reduce((prev, curr) => prev + (curr.skipped ? -1 : curr.points), 0)
            + team.bonus,
          lastSubmit: teamSubmits.reduce((prev, curr) => {
            const currDate = new Date(curr.inserted);
            return currDate > prev ? currDate : prev;
          }, new Date(0)),
        };
      });
    } else {
      return null;
    }
  }, [data]);
}
