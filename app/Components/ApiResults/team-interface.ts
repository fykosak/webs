export interface Team {
  teamId: number;
  name: string;
  category: string;
  participants: Participant[];
}

export interface Participant {
  name: string;
  schoolName: string;
  countryIso: string;
}
