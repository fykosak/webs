import React from 'react';
import ReactDOM from 'react-dom';
import {App} from "./app";

const element = document.getElementById("results-panel");
const countdownElement = document.getElementById("countdown-portal");

export const LangContext = React.createContext<string>('lang');
export const CountdownPortalContext = React.createContext<HTMLElement | null>(null);

if (element) {
  ReactDOM.render(
    <React.StrictMode>
      <LangContext.Provider value={element.getAttribute("data-lang")}>
        <CountdownPortalContext.Provider value={countdownElement}>
          <App
            url={element.getAttribute("data-url")}
            teams={JSON.parse(element.getAttribute("data-teams"))}
            results={JSON.parse(element.getAttribute("data-results"))}
          />
        </CountdownPortalContext.Provider>
      </LangContext.Provider>
    </React.StrictMode>,
    element
  )
}
