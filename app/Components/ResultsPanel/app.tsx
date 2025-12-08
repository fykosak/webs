import React, { memo, useCallback, useContext, useEffect, useMemo, useState } from 'react';
import { createPortal } from 'react-dom';
import { useData } from '../ApiResults/use-data';
import { useTeamPoints } from '../ApiResults/use-team-points';
import { CountdownPortalContext, LangContext } from './main';
import { Team } from '../ApiResults/team-interface';
import { DataInterface, isDataInterfaceVisible } from '../ApiResults/data-interface';

export const App: React.FC<{ url: string, teams: Team[], results: DataInterface }> = memo(({ url, teams, results }) => {
    const data = useData(url, results);
    const showLive = data && new Date(data.times.gameEnd).getTime() > new Date().getTime();
    const lang = useContext(LangContext);
    return <>
        {showLive && <p>{lang === 'cs' ? 'Výsledky jsou živě ze soutěže' : 'Results are live from the competition'}</p>}
        {isDataInterfaceVisible(data) ? <ForVisibleResults data={data} teams={teams} /> :
            <ForHiddenResults data={data} />}
        <CountDownPortal results={results} />
    </>
});

const CountDownPortal: React.FC<{ results: DataInterface }> = memo(({ results }) => {
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
        {String(hours).padStart(2, '0')}:
        {String(minutes).padStart(2, '0')}:
        {String(seconds).padStart(2, '0')}
    </span>, element);
});

/**
 * Generates SQL for FKSDB because it seemed faster than generating on the server side
 * @param points
 */
function generateSQL(points: ReturnType<typeof useTeamPoints> | null) {
    let participated = [12390, 12393, 12394, 12396, 12397, 12398, 12399, 12403, 12404, 12405, 12408, 12409, 12412, 12413, 12415, 12418, 12419, 12421, 12424, 12425, 12430, 12433, 12434, 12435, 12436, 12438, 12439, 12441, 12450, 12453, 12458, 12462, 12464, 12466, 12470, 12471, 12472, 12474, 12475, 12477, 12478, 12479, 12480, 12482, 12484, 12485, 12488, 12489, 12490, 12492, 12494, 12495, 12496, 12501, 12508, 12511, 12512, 12513, 12514, 12515, 12516, 12518, 12519, 12522, 12523, 12532, 12533, 12536, 12538, 12543, 12544, 12547, 12548, 12549, 12553, 12555, 12557, 12560, 12561, 12562, 12563, 12565, 12566, 12567, 12569, 12571, 12576, 12577, 12578, 12585, 12586, 12587, 12592, 12596, 12597, 12598, 12599, 12600, 12602, 12603, 12604, 12605, 12606, 12607, 12608, 12609, 12618, 12620, 12621, 12622, 12624, 12626, 12628, 12630, 12633, 12634, 12636, 12639, 12640, 12642, 12643, 12644, 12646, 12650, 12657, 12665, 12667, 12669, 12676, 12683, 12684, 12685, 12686, 12687, 12688, 12689, 12690, 12691, 12692, 12695, 12696, 12698, 12699, 12701, 12702, 12703, 12704, 12705, 12708, 12710, 12711, 12712, 12714, 12715, 12716, 12721, 12723, 12735, 12738, 12739, 12746, 12749, 12752, 12753, 12757, 12758, 12759, 12760, 12761, 12762, 12763, 12764, 12767, 12769, 12770, 12773, 12774, 12776, 12777, 12778, 12779, 12783, 12784, 12790, 12793, 12795, 12796, 12798, 12799, 12803, 12809, 12810, 12815, 12816, 12819, 12820, 12821, 12823, 12824, 12825, 12826, 12827, 12834, 12837, 12838, 12839, 12840, 12842, 12843, 12847, 12848, 12849, 12850, 12851, 12853, 12854, 12855, 12856, 12857, 12858, 12859, 12860, 12861, 12862, 12863, 12864, 12865, 12867, 12868, 12869, 12871, 12872, 12879, 12880, 12882, 12884, 12885, 12886, 12887, 12888, 12895, 12896, 12897, 12898, 12904, 12905, 12906, 12907, 12908, 12911, 12912, 12913, 12914, 12922, 12923, 12924, 12925, 12927, 12928, 12931, 12932, 12933, 12936, 12939, 12942, 12943, 12946, 12947, 12948, 12949, 12950, 12951, 12952, 12958, 12961, 12962, 12963, 12964, 12965, 12966, 12967, 12968, 12971, 12973, 12974, 12977, 12979, 12981, 12982, 12983, 12984, 12985, 12987, 12988, 12989, 12992, 12993, 12994, 12995, 12997, 13000, 13002, 13004, 13005, 13006, 13007, 13008, 13009, 13010, 13013, 13015, 13016, 13018, 13019, 13021, 13022, 13023, 13025, 13026, 13028, 13030, 13031, 13032, 13035, 13036, 13038, 13039, 13040, 13042, 13043, 13045, 13046, 13047, 13048, 13049, 13051, 13052, 13055, 13057, 13060, 13062, 13063, 13065, 13066, 13067, 13070, 13071, 13072, 13074, 13075, 13079, 13088, 13090, 13091, 13093, 13094, 13095, 13097, 13098, 13100, 13101, 13102, 13104, 13105, 13106, 13107, 13109, 13110, 13111, 13112, 13113, 13114, 13115, 13116, 13117, 13118, 13120, 13121, 13122, 13123, 13126, 13127, 13133, 13136, 13137, 13138, 13141, 13142, 13144, 13145, 13147, 13149, 13150, 13153, 13156, 13157, 13158, 13162, 13163, 13164, 13166, 13167, 13168, 13169, 13170, 13171, 13172, 13173, 13175, 13176, 13180, 13181, 13183, 13184, 13185, 13187, 13188, 13189, 13190, 13191, 13193, 13194, 13195, 13196, 13197, 13198, 13199, 13202, 13203, 13214, 13215, 13216, 13217, 13219, 13220, 13221, 13222, 13223, 13224, 13225, 13226, 13227, 13228, 13229, 13230, 13232, 13233, 13234, 13237, 13238, 13239, 13242, 13243, 13245, 13246, 13247, 13248, 13249, 13250, 13253, 13254, 13256, 13257, 13258, 13261, 13265, 13266, 13267, 13268, 13269, 13270, 13274, 13275, 13276, 13277, 13278, 13279, 13280, 13281, 13282, 13283, 13284, 13285, 13286, 13291, 13296, 13297, 13299, 13303, 13311, 13312, 13313, 13314, 13316, 13317, 13319, 13325, 13326, 13328, 13329, 13330, 13331, 13332, 13333, 13334, 13335, 13336, 13337, 13338, 13342, 13343, 13347, 13349, 13350, 13352, 13354, 13355, 13356, 13358, 13359, 13360, 13362, 13365, 13366, 13367, 13368, 13369, 13370, 13371, 13372, 13373, 13374, 13375, 13377, 13378, 13381, 13382, 13387, 13388, 13389, 13390, 13391, 13392, 13393, 13394, 13395, 13396, 13397, 13398, 13399, 13400, 13402, 13403, 13404, 13407, 13408, 13411, 13415, 13419, 13420, 13421, 13422, 13423, 13424, 13426, 13427, 13428, 13429, 13430, 13431, 13432, 13436, 13437, 13438, 13440, 13445, 13446, 13447, 13448, 13450, 13451, 13452, 13453, 13454, 13455, 13456, 13457, 13459, 13461, 13462, 13464, 13465, 13466, 13467, 13468, 13470, 13471, 13472, 13473, 13476, 13477, 13478, 13479, 13481, 13482, 13483, 13484, 13485, 13486, 13487, 13488, 13489, 13490, 13491, 13492, 13494, 13495, 13496, 13497, 13498, 13500, 13501, 13502, 13505, 13508, 13509, 13512, 13513, 13514, 13515, 13516, 13517, 13521, 13522, 13524, 13525, 13526, 13527, 13528, 13529, 13530, 13531, 13532, 13533, 13534, 13535, 13538, 13541, 13542, 13543, 13544, 13545, 13546, 13548, 13549, 13550, 13551, 13552, 13553, 13554, 13555, 13556, 13558, 13560, 13561, 13562, 13563, 13564, 13565, 13566, 13567, 13569, 13570, 13571, 13572, 13573, 13575, 13577, 13578, 13579, 13580, 13581, 13582, 13583, 13584, 13585, 13586, 13587, 13592, 13593, 13594, 13595, 13596, 13598, 13599, 13600, 13602, 13603, 13608, 13610, 13611, 13612, 13613, 13614, 13615, 13618, 13619, 13622, 13623, 13624, 13625, 13627, 13630, 13631, 13633, 13634, 13635, 13638, 13639, 13640, 13642, 13643, 13644, 13645, 13646, 13648, 13649, 13650, 13651, 13652, 13653, 13655, 13656, 13657, 13658, 13659, 13661, 13664, 13668, 13669, 13670, 13671, 13672, 13674, 13675, 13676, 13677, 13678, 13679, 13680, 13681, 13682, 13684, 13685, 13686, 13687, 13688, 13689, 13690, 13691, 13692, 13693, 13694, 13695, 13696, 13697, 13698, 13700, 13702, 13703, 13704, 13706, 13707, 13708, 13711, 13713, 13714, 13715, 13716, 13718, 13719, 13721, 13722, 13723, 13724, 13725, 13726, 13727, 13728, 13729, 13730, 13731, 13733, 13734, 13737, 13738, 13739, 13740, 13744, 13745, 13746, 13747, 13749, 13750, 13751, 13752, 13754, 13755, 13756, 13757, 13758, 13760, 13761, 13762, 13764, 13765, 13766, 13769, 13770, 13772, 13774, 13775, 13779, 13781, 13782, 13783, 13784, 13785, 13787, 13788, 13789, 13790, 13791, 13793, 13794, 13795, 13798, 13799, 13800, 13801, 13802, 13803, 13804, 13805, 13806, 13807, 13808, 13809, 13810, 13811, 13812, 13813, 13814, 13815, 13819, 13824, 13825, 13826, 13827, 13828, 13829, 13830, 13831, 13832, 13833, 13834, 13835, 13836, 13837, 13838, 13839, 13840, 13841, 13842, 13844, 13845, 13846, 13847, 13848, 13849, 13850, 13852, 13853, 13854, 13855, 13856, 13858, 13859, 13860, 13861, 13862, 13863, 13865, 13867, 13868, 13870, 13872, 13874, 13877, 13878, 13881, 13882, 13883, 13884, 13885, 13886, 13887, 13888, 13890, 13891, 13892, 13893, 13894, 13895, 13896, 13897, 13898, 13899, 13903, 13904, 13906, 13907, 13908, 13909, 13910, 13912, 13913, 13914, 13915, 13916, 13919, 13920, 13924, 13926, 13928, 13929, 13930, 13931, 13932, 13933, 13934, 13935, 13936, 13937, 13938, 13939, 13940, 13941, 13942, 13943, 13944, 13945, 13946, 13949, 13950, 13951, 13952, 13953, 13954, 13956, 13957, 13958, 13960, 13964, 13966, 13968, 13969, 13970, 13972, 13974, 13975, 13976, 13977, 13980, 13981, 13982, 13983, 13986, 13987, 13989, 13990, 13991, 13992, 13993, 13994, 13995, 13999, 14000, 14003, 14004, 14006, 14007, 14008, 14009, 14010, 14012, 14014, 14015, 14016, 14017, 14018, 14019, 14021, 14022, 14023, 14024, 14025, 14026, 14028, 14029, 14030, 14031, 14032, 14033, 14034, 14035, 14036, 14037, 14038, 14039, 14040, 14049, 14050, 14051, 14052, 14053, 14056, 14057, 14058, 14059, 14060, 14061, 14062, 14063, 14064, 14067, 14070, 14071, 14072, 14073, 14076, 14077, 14078, 14079, 14080, 14081, 14082, 14083, 14084, 14085, 14086, 14087, 14089, 14090, 14091, 14092, 14094, 14096, 14097, 14098, 14099, 14100, 14101, 14102, 14106, 14107, 14108, 14109, 14110, 14111, 14112, 14113, 14114, 14115, 14116, 14118, 14119, 14120, 14122, 14123, 14124, 14125, 14126, 14127, 14128, 14130, 14131, 14132, 14133, 14134, 14137, 14138, 14139, 14140, 14141, 14142, 14143, 14145, 14146, 14147, 14148, 14149, 14150, 14151, 14153, 14154, 14155, 14156, 14157, 14159, 14160, 14162, 14164, 14165, 14166, 14167, 14168, 14169, 14170, 14173, 14174, 14175, 14179, 14180, 14181, 14184, 14185, 14186, 14187, 14188, 14189, 14190, 14193, 14194, 14195, 14196, 14197, 14198, 14199, 14201, 14202, 14204, 14205, 14206, 14207, 14208, 14209, 14210, 14213, 14214, 14215, 14219, 14220, 14221, 14223, 14224, 14225, 14226, 14228, 14229, 14233, 14234, 14235, 14236, 14238, 14239, 14240, 14242, 14244, 14245, 14246, 14249, 14250, 14251, 14254, 14255, 14257, 14259, 14260, 14261, 14262, 14263, 14264, 14265, 14267, 14268, 14269, 14270, 14271, 14273, 14274, 14275, 14276, 14277, 14278, 14282, 14283, 14285, 14288, 14289, 14291, 14292, 14293, 14294, 14295, 14296, 14297, 14300, 14301, 14308, 14309, 14310, 14311, 14312, 14313, 14314, 14316, 14317, 14318, 14322, 14323, 14324, 14325, 14327, 14329, 14331, 14332, 14333];
    let disqualified = [13247, 12687, 13494, 13492, 12714, 13331, 13856, 12922, 12633, 13572, 13404, 12563, 14010, 13055, 12973, 12767, 14007, 13684, 14332, 14329, 12562, 13171, 13907, 14312, 12514, 13402, 12861, 14323, 12699, 13233, 14102, 12412, 12462, 13815, 13311, 13852, 12799, 13881, 12862, 13581, 13286, 12684, 13002, 13645, 13964, 13534, 13118, 14244, 12896, 12914, 14292, 13013, 13319, 13868, 13149, 14193, 13314, 13454, 13347, 13652, 13136, 12425, 14101, 13623, 13338, 14262, 13466, 14285, 12887, 12964, 13101, 13703, 13916, 12622, 13656, 12753, 13649, 13685, 14327, 12466, 12624, 13888, 13774, 13861, 13373, 13023, 13845, 12592, 13445, 13806, 14249, 13010, 12695, 13392, 14263, 12942, 13246, 13613, 13243, 13831, 12482, 12795, 12488, 13668, 13126, 13015, 13038, 13733, 12710, 12471, 13261, 13337, 13542, 13631, 13727, 13895, 12971, 13757, 13644, 13808, 14024, 14169, 14186, 12796, 12906, 12871, 12803, 12676, 12867, 13072, 13266, 13343, 13446, 13794, 13836, 13144, 14119, 13411, 14228, 13498, 12779, 14277, 13802, 14008, 12586, 13342, 13920, 13586, 14213, 14147, 13202, 13804, 14116, 12962, 13945, 13145, 13700, 12561, 14107, 13622, 12470, 13391, 13367, 13716, 14202, 12702, 12642, 13133, 13592, 13593, 12496, 12639, 14112, 12477, 13262]
    let query = '';
    let csv = 'team_id,name,category,points,rankCategory,rankTotal\n';

    function addTeam(team: ReturnType<typeof useTeamPoints>[number]['team'], state: string, points: number | null, rankCategory: number | null, rankTotal: number | null) {
        query += `UPDATE fyziklani_team
                  SET state         = '${state}',
                      points        = ${points ?? 'NULL'},
                      rank_category = ${rankCategory ?? 'NULL'},
                      rank_total    = ${rankTotal ?? 'NULL'}
                  WHERE fyziklani_team_id = ${team.teamId}
                    AND event_id = 200;  `;
        csv += `${team.teamId},"${team.name.replace(/"/g, '""')}",${team.category},${points},${rankCategory},${rankTotal}\n`;
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
        const state = disqualified.includes(team.team.teamId) ? 'disqualified' : participated.includes(team.team.teamId) ? 'participated' : 'missed';
        const rankTotal = sorted_participated.slice(0, i).filter(t => !disqualified.includes(t.team.teamId)).length + 1;
        const rankCategory = sorted_participated.slice(0, i).filter(t => t.team.category === team.team.category && !disqualified.includes(t.team.teamId)).length + 1;
        addTeam(team.team, state, state == 'participated' ? team.points : null, state == 'participated' ? rankCategory : null, state == 'participated' ? rankTotal : null);
    }

    // writes the output to the browser console
    console.log(query);
    console.log(csv);
}

const useToggle = (initialState: boolean = false): [boolean, () => void] => {
    const [state, setState] = useState(initialState);
    const toggle = useCallback(() => setState(state => !state), []);
    return [state, toggle];
}

export const ForVisibleResults: React.FC<{ data: DataInterface<true>, teams: Team[] }> = ({ data, teams }) => {
    const points = useTeamPoints(data);
    const [showFull, toggleShowFull] = useToggle();
    const lang = useContext(LangContext);
    const mappedTeams = useMemo(() => Object.fromEntries(teams.map(t => [t.teamId, t])), [teams]);

    return <>
        <div className="row strips">
            {data.categories.map(c =>
                <div className="col-md">
                    <CategoryColumn category={c} points={points} showFull={showFull} mappedTeams={mappedTeams} />
                </div>,
            )}
        </div>
        {showFull && false && <div className="row">
            <CategoryColumn category={'O'} points={points} showFull={true} mappedTeams={mappedTeams} />
        </div>}
        <button onClick={toggleShowFull} className="btn btn-panel-action">{showFull ? (
            lang === 'cs' ? 'Skrýt' : 'Hide'
        ) : (
            lang === 'cs' ? 'Zobrazit všechny týmy' : 'Show all teams'
        )}</button>
    </>;
}

export const ForHiddenResults: React.FC<{ data: DataInterface }> = memo(({ data }) => {
    const lang = useContext(LangContext);
    return <div className={'hidden-results'}>
        {lang === 'cs' ? 'Výsledky jsou před koncem soutěže skryté.' : 'Results are hidden before the end of the competition.'}
    </div>;
});

const CATEGORY_NAMES = {
    A: 'A',
    B: 'B',
    C: 'C',
    O: 'Open',
}

const CategoryColumn: React.FC<{
    category: string,
    points: ReturnType<typeof useTeamPoints> | null,
    showFull: boolean,
    mappedTeams: Record<number, Team>,
}>
    = memo(({ category, points, showFull, mappedTeams }) => {
        const lang = useContext(LangContext);
        // generateSQL(points); // writes the output to the browser console

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
            },
            ).filter((_, i) => showFull || i < 10), [points, showFull]);

        return <>
            <div className="category-title">
                {lang === 'cs' ?
                    <>Kategorie {CATEGORY_NAMES[category as keyof typeof CATEGORY_NAMES]}</> :
                    <>{CATEGORY_NAMES[category as keyof typeof CATEGORY_NAMES]} category</>
                }
            </div>
            <table>
                {sorted?.map((p, i) => <tr className={p.points || p.team.disqualified ? '' : 'zero-points'}>
                    <td>{p.team.disqualified ? 'DSQ' : (p.points ? `${i + 1}.` : '-')}</td>
                    <td>
                        <div className="team-name">{mappedTeams[p.team.teamId]?.name ?? p.team.name}</div>
                        <div className="flags">
                            {[...new Set(mappedTeams[p.team.teamId]?.participants.map(p => p.countryIso))].filter(iso => iso !== '').map(iso =>
                                <span className={`fi fi-${iso?.toLowerCase()}`} />,
                            )}
                        </div>
                    </td>
                    <td>{p.team.disqualified ? 'x' : p.points}</td>
                </tr>)}
            </table>
        </>;
    });
