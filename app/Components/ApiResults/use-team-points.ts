import { DataInterface, Team } from './data-interface';
import { useMemo } from 'react';

export const useTeamPoints = (data: DataInterface<true> | null): {team: Team<true>, points: number, lastSubmit: Date}[] | null => {
  return useMemo(() => {
    if (data) {
      let tasks : number[] = [];
      data.tasks.forEach(task => {
        tasks.push(task.taskId);
      })
      return data.teams.map(team => {
        let submits = Object.values(data.submits);
        const teamSubmits = submits.filter(submit => submit.teamId === team.teamId && tasks.includes(submit.taskId));
        return {
          team,
          points: teamSubmits.reduce((prev, curr) => prev + (curr.skipped ? -1 : curr.points), 0)
            + (team.bonus ?? 0),
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
