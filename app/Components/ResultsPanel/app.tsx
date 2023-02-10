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

/**
 * Generates SQL for FKSDB because it seemed faster than generating on the server side
 * @param points
 */
function generateSQL(points: ReturnType<typeof useTeamPoints> | null) {
  let participated = [5759, 5763, 5765, 5766, 5767, 5768, 5769, 5771, 5772, 5773, 5774, 5780, 5781, 5783, 5789, 5792, 5793, 5795, 5796, 5811, 5813, 5814, 5816, 5817, 5818, 5820, 5821, 5822, 5823, 5825, 5827, 5828, 5829, 5830, 5834, 5835, 5836, 5841, 5842, 5843, 5845, 5848, 5854, 5857, 5858, 5861, 5865, 5866, 5868, 5870, 5874, 5875, 5884, 5888, 5890, 5897, 5901, 5902, 5903, 5906, 5908, 5909, 5911, 5913, 5919, 5923, 5928, 5929, 5930, 5931, 5932, 5933, 5937, 5938, 5941, 5942, 5944, 5948, 5951, 5952, 5953, 5955, 5956, 5957, 5958, 5965, 5966, 5967, 5969, 5979, 5980, 5981, 5983, 5989, 5993, 5994, 5995, 5996, 5997, 5999, 6001, 6002, 6005, 6008, 6009, 6010, 6011, 6012, 6013, 6015, 6018, 6019, 6020, 6022, 6024, 6025, 6027, 6028, 6032, 6034, 6035, 6036, 6037, 6039, 6043, 6044, 6045, 6046, 6048, 6049, 6050, 6051, 6053, 6054, 6056, 6057, 6062, 6063, 6064, 6065, 6066, 6068, 6069, 6070, 6071, 6072, 6074, 6075, 6077, 6081, 6082, 6083, 6084, 6085, 6086, 6088, 6099, 6100, 6102, 6103, 6106, 6107, 6109, 6110, 6111, 6114, 6115, 6117, 6119, 6121, 6122, 6123, 6126, 6127, 6128, 6129, 6130, 6131, 6132, 6135, 6136, 6137, 6138, 6139, 6141, 6142, 6145, 6146, 6147, 6148, 6149, 6150, 6151, 6152, 6153, 6154, 6155, 6156, 6157, 6158, 6160, 6161, 6162, 6163, 6164, 6165, 6167, 6168, 6169, 6170, 6171, 6172, 6173, 6174, 6175, 6176, 6177, 6178, 6179, 6180, 6181, 6182, 6183, 6184, 6185, 6186, 6187, 6188, 6189, 6194, 6196, 6197, 6199, 6200, 6201, 6203, 6204, 6205, 6208, 6209, 6210, 6212, 6216, 6217, 6218, 6219, 6220, 6221, 6222, 6223, 6224, 6225, 6226, 6227, 6228, 6230, 6231, 6232, 6233, 6234, 6235, 6237, 6238, 6240, 6241, 6242, 6243, 6244, 6245, 6246, 6247, 6248, 6249, 6250, 6251, 6252, 6258, 6259, 6260, 6261, 6262, 6263, 6265, 6266, 6267, 6268, 6269, 6270, 6271, 6272, 6273, 6275, 6276, 6277, 6278, 6279, 6280, 6281, 6282, 6283, 6284, 6285, 6286, 6287, 6288, 6289, 6290, 6291, 6292, 6293, 6294, 6295, 6296, 6297, 6298, 6300, 6301, 6302, 6303, 6304, 6305, 6306, 6307, 6308, 6309, 6310, 6311, 6312, 6313, 6314, 6315, 6316, 6317, 6318, 6319, 6320, 6323, 6324, 6325, 6326, 6327, 6329, 6330, 6331, 6332, 6333, 6334, 6336, 6337, 6338, 6339, 6340, 6341, 6342, 6343, 6344, 6345, 6346, 6347, 6348, 6349, 6350, 6351, 6352, 6353, 6354, 6355, 6356, 6358, 6359, 6360, 6361, 6362, 6363, 6364, 6365, 6367, 6368, 6369, 6371, 6372, 6373, 6374, 6375, 6376, 6377, 6378, 6381, 6382, 6383, 6384, 6385, 6387, 6388, 6389, 6390, 6391, 6392, 6393, 6395, 6396, 6398, 6399, 6400, 6401, 6402, 6403, 6404, 6405, 6407, 6408, 6409, 6410, 6412, 6413, 6417, 6418, 6419, 6420, 6421, 6422, 6423, 6425, 6427, 6428, 6429, 6430, 6431, 6432, 6433, 6434, 6436, 6437, 6438, 6439, 6440, 6441, 6442, 6443, 6444, 6445, 6446, 6447, 6448, 6449, 6450, 6451, 6452, 6454, 6455, 6456, 6457, 6458, 6459, 6460, 6461, 6462, 6463, 6464, 6465, 6466, 6467, 6469, 6471, 6472, 6473, 6474, 6475, 6476, 6478, 6480, 6481, 6482, 6483, 6484, 6487, 6488, 6489, 6490, 6491, 6492, 6493, 6494, 6495, 6496, 6501, 6502, 6503, 6504, 6505, 6506, 6508, 6509, 6510, 6511, 6513, 6515, 6516, 6517, 6519, 6520, 6521, 6522, 6523, 6524, 6525, 6526, 6528, 6529, 6530, 6534, 6535, 6541, 6542, 6543, 6544, 6545, 6546, 6547, 6548, 6549, 6551, 6552, 6553, 6554, 6555, 6556, 6557, 6558, 6560, 6561, 6562, 6564, 6565, 6566, 6567, 6568, 6569, 6570, 6587, 6588, 6589, 6590, 6591, 6594, 6595, 6596, 6598, 6599, 6600, 6601, 6602, 6603, 6604, 6605, 6606, 6607, 6608, 6609, 6610, 6611, 6612, 6613, 6614, 6615, 6616, 6617, 6619, 6625, 6630, 6631, 6633, 6639, 6640, 6641, 6645, 6646, 6647, 6648, 6649, 6650, 6651, 6652, 6653, 6654, 6655, 6656, 6657, 6658, 6659, 6661, 6663, 6665, 6667, 6668, 6669, 6670, 6671, 6672, 6673, 6674, 6675, 6676, 6677, 6678, 6681, 6682, 6683, 6684, 6685, 6686, 6687, 6689, 6690, 6691, 6692, 6693, 6694, 6696, 6698, 6700, 6701, 6702, 6704, 6705, 6706, 6707, 6709, 6710, 6711, 6712, 6713, 6714, 6715, 6716, 6717, 6718, 6719, 6720, 6721, 6722, 6723, 6724, 6725, 6726, 6727, 6728, 6729, 6730, 6731, 6732, 6733, 6734, 6735, 6736, 6737, 6738, 6739, 6740, 6741, 6742, 6743, 6744, 6745, 6746, 6747, 6748, 6749, 6750, 6751, 6754, 6756, 6757, 6758, 6759, 6760, 6761, 6762, 6763, 6764, 6767, 6770, 6773, 6774, 6775, 6776, 6777, 6778, 6783, 6784, 6785, 6786, 6787, 6788, 6789, 6790, 6791, 6793, 6794, 6795, 6796, 6798, 6799, 6801, 6802, 6803, 6804, 6806, 6807, 6808, 6809, 6810, 6815, 6816, 6817, 6819, 6820, 6821, 6822, 6823, 6824, 6825, 6826, 6827, 6828, 6829, 6830, 6831, 6832, 6833, 6834, 6835, 6836, 6837, 6838, 6839, 6840, 6841, 6842, 6844, 6845, 6846, 6847, 6848, 6849, 6850, 6851, 6853, 6854, 6855, 6856, 6857, 6858, 6859, 6861, 6862, 6863, 6864, 6865, 6866, 6867, 6868, 6869, 6871, 6872, 6873, 6874, 6876, 6877, 6878, 6879, 6880, 6881, 6887, 6888, 6890, 6892, 6894, 6895, 6896, 6897, 6898, 6899, 6900, 6902, 6903, 6904, 6905, 6907, 6908, 6909, 6910, 6912, 6913, 6914, 6915, 6916, 6917, 6918, 6919, 6920, 6921, 6922, 6923, 6924, 6925, 6928, 6929, 6930, 6931, 6932, 6935, 6936, 6937, 6938, 6939, 6940, 6941, 6942, 6943, 6944, 6945, 6946, 6947, 6948, 6949, 6952, 6953, 6954, 6955, 6956, 6957, 6959, 6960, 6961, 6962, 6963, 6964, 6965, 6966, 6968, 6969, 6970, 6971, 6973, 6974, 6975, 6976, 6977, 6978, 6979, 6980, 6981, 6982, 6984, 6985, 6987, 6988, 6991, 6992, 6993, 6994, 6995, 6996, 6997, 6998, 6999, 7000, 7001, 7002, 7003, 7004, 7005, 7006, 7007, 7008, 7009, 7010, 7011, 7013, 7014, 7015, 7016, 7017, 7018, 7019, 7020, 7023, 7024, 7025, 7028, 7029, 7030, 7031, 7033, 7035, 7037, 7039, 7040, 7041, 7042, 7043, 7044, 7045];
  let disqualified = [6807,5818,5820,5821,5874,6956,5969,6410,5966,6368,6489,5903,5906,5938,6385,5997,6053,6109,6208,7033,7044,6119,6139,6502,6815,6316,6878,6231,6246,5928,6336,6790,6910,6942,6890,6932,6523,6535,6403,6747]
  let query = "";
  function addTeam(team: number, state: string, points: number | null, rankCategory: number | null, rankTotal: number | null) {
    query += `UPDATE fyziklani_team SET state = '${state}', points = ${points ?? "NULL"}, rank_category = ${rankCategory ?? "NULL"}, rank_total = ${rankTotal ?? "NULL"} WHERE fyziklani_team_id = ${team} AND event_id = 170;\n`;
  }

  const sorted = points.sort((a, b) => {
    if (a.team.disqualified !== b.team.disqualified) {
      return (a.team.disqualified ? 1 : 0) - (b.team.disqualified ? 1 : 0);
    }
    if (a.points === b.points) {
      return a.lastSubmit > b.lastSubmit ? 1 : -1;
    } else {
      return b.points - a.points;
    }
  });

  const sorted_participated = sorted.filter(t => participated.includes(t.team.teamId));

  for (let i = 0; i < sorted_participated.length; i++) {
    const team = sorted_participated[i];
    const rankTotal = i + 1;
    const rankCategory = sorted_participated.slice(0, i).filter(t => t.team.category === team.team.category).length + 1;
    const state = disqualified.includes(team.team.teamId) ? "disqualified" : participated.includes(team.team.teamId) ? "participated" : "missed";
    addTeam(team.team.teamId, state, state == "participated" ? team.points : null, state == "participated" ? rankCategory : null, state == "participated" ? rankTotal : null);
  }

  console.log(query); // writes the output to the browser console
}

export const ForVisibleResults: React.FC<{data: DataInterface<true>, teams: Team[]}> = ({data, teams}) => {
  const points = useTeamPoints(data);
  const [showFull, toggleShowFull] = useToggle();
  const lang = useContext(LangContext);
  const mappedTeams = useMemo(() => Object.fromEntries(teams.map(t => [t.teamId, t])), [teams]);

  return <>
    <div className="row strips">
      {["A", "B", "C"].map(c =>
        <div className="col-md-4">
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
  //generateSQL(points); // writes the output to the browser console

  const sorted = useMemo(() => points
    ?.filter(p => p.team.category === category)
    .sort((a, b) => {
        if (a.team.disqualified !== b.team.disqualified) {
          return (a.team.disqualified ? 1 : 0) - (b.team.disqualified ? 1 : 0);
        }
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
      {sorted?.map((p, i) => <tr className={p.points || p.team.disqualified ? "" : "zero-points"}>
        <td>{p.team.disqualified ? "DSQ" : (p.points ? `${i + 1}.` : '-')}</td>
        <td>
          <div className="team-name">{mappedTeams[p.team.teamId]?.name ?? p.team.name}</div>
          <div className="flags">
            {[...new Set(mappedTeams[p.team.teamId]?.participants.map(p => p.countryIso))].filter(iso => iso !== "ZZ").map(iso =>
              <span className={`flag-icon flag-icon-${iso.toLowerCase()}`} />
            )}
          </div>
        </td>
        <td>{p.team.disqualified ? "x" : p.points}</td>
      </tr> )}
    </table>
  </>;
});
