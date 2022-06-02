import React, {Suspense} from 'react';
import ReactDOM from 'react-dom';

// import MainComponent from './components/mainComponent';
// 
const init = ()=> {
    try {
        const MainComponent = React.lazy(()=> import('./components/mainComponent'));
        ReactDOM.render(
            <Suspense fallback={<div>....</div>}>
                <MainComponent />
            </Suspense>,
            document.getElementById("list_category_mobile"));
    } catch(err) {
        
    }
}
export default init;