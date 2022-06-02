import React, { lazy, Suspense } from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
// import store
import {BuildPcStore} from './stores/indexStore';

// import container
// import MainComponent from './components/mainComponent';

const init = ()=> {
    try {
        const MainComponent = lazy(()=> import('./components/mainComponent'));
        ReactDOM.render(
            <Provider store={BuildPcStore}>
                <Suspense fallback={<div>...</div>}>
                <MainComponent />
                </Suspense>
            </Provider>, 
            document.getElementById('build-pc-function'));
    } catch (err) {
        //
    }
}

export default init;