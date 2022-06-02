import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import {BuildPcStoreBM} from './stores/indexStore'
import MainComponent from './components/mainComponent';
try {
    if (document.getElementById('select_product')) {
        ReactDOM.render(
            <Provider store={BuildPcStoreBM}>
                <MainComponent />
            </Provider>, 
            document.getElementById('select_product') );
    }
} catch( err ) {

}