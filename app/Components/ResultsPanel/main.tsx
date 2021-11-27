import React from 'react';
import { App } from './app';
import { NetteActions } from '../../../vendor/fykosak/nette-frontend-component/src/NetteActions/netteActions';
import { DataInterface } from '../ApiResults/data-interface';


export const LangContext = React.createContext<string>('lang');
export const CountdownPortalContext = React.createContext<HTMLElement | null>(null);

const Main: React.FC<{ data: DataInterface, actions: NetteActions }> = (params) => {
    console.log(params);
    const {data: {teams,lang}, data, actions} = params;
    const countdownElement = document.getElementById('countdown-portal');

    return <React.StrictMode>
        <LangContext.Provider value={lang}>
            <CountdownPortalContext.Provider value={countdownElement}>
                <App
                    url={actions.getAction('refresh')}
                    // @ts-ignore
                    teams={teams}
                    results={data}
                />
            </CountdownPortalContext.Provider>
        </LangContext.Provider>
    </React.StrictMode>;
}
export default Main;
