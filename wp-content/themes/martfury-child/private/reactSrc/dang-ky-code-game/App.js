import React, {Suspense} from 'react';
import ReactDOM from 'react-dom';

const init = ()=> {
    try {
        document.body.classList.add('installment-data');
        const MainComponent = React.lazy(()=> import('./components/mainComponent'));
            ReactDOM.render(
                <Suspense fallback={<div>....</div>}>
                    <MainComponent />
                </Suspense>, 
            document.getElementById('dang-ky-code-game'));
    } catch (err) {
        //
    }
}

export default init;