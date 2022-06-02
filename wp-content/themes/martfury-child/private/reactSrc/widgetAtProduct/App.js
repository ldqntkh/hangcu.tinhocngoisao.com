import React from 'react';
import ReactDOM from 'react-dom';

import MainComponent from './components/mainComponent';

const init = (elementId)=> {
    try {
        ReactDOM.render(
            <MainComponent id={elementId} />,
            document.getElementById(elementId));
    } catch(err) {
        
    }
}
export default init;