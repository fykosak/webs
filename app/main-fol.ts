import './Modules/Fol/Core/styles/main.scss';
import 'bootstrap';
import Renderer from '../vendor/fykosak/nette-frontend-component/src/Loader/Renderer'
import './Modules/Fol/DefaultModule/scripts/faq.js';
import './Components/Countdown/countdown';
import './Modules/Fol/ArchiveModule/assets/reports';
import Main from './Components/ResultsPanel/main';

const renderer = new Renderer();
renderer.hashMapLoader.registerActionsComponent('api.results', Main);
renderer.run();
