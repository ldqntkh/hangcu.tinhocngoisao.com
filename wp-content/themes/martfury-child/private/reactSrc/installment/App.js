import React, {Suspense} from 'react';
import ReactDOM from 'react-dom';
// import container
// import MainComponent from './components/mainComponent.js';

const init = ()=> {
    try {
        document.body.classList.add('installment-data');
        const MainComponent = React.lazy(()=> import('./components/mainComponent'));
            ReactDOM.render(
                <Suspense fallback={<div>....</div>}>
                    <MainComponent />
                </Suspense>, 
            document.getElementById('installment'));
    } catch (err) {
        //
    }
}

export default init;