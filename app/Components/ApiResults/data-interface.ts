export interface Team<Visible extends boolean> {
  teamId: number;
  name: string;
  category: string;
  disqualified: boolean;
  participated: boolean;
  bonus: Visible extends true ? number : null;
}

export interface Task {
  taskId: number;
  group: number;
  number: number;
  points: number;
  name: string;
}

export interface Submit {
  taskId: number;
  teamId: number;
  skipped: number;
  points: number;
  inserted: string;
}

export interface Times<Visible extends boolean> {
  gameEnd: string;
  gameStart: string;
  toEnd: number;
  toStart: number;
  visible: boolean;
}

export interface DataInterface<Visible extends boolean = false> {
  gameStart: string;
  gameEnd: string;
  availablePoints: number[];
  categories: string[];
  basePath: string;
  teams: Team<Visible>[];
  tasks: Task[];
  submits: Visible extends true ? Submit[] : null;
  refreshDelay: number;
  times: Times<Visible>;
}

export function isDataInterfaceVisible(data: DataInterface<boolean>): data is DataInterface<true> {
  return data?.times.visible;
}
