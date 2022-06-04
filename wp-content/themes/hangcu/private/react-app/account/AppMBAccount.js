import React, { lazy, Suspense } from 'react';
import ReactDom from 'react-dom';
// import MainMBFormComponent from './components/mobile/MainMBFormComponent';
// import MB_MainAccountComponent from './components/mobile/MainAccountComponent';


if( document.getElementById('account-page-mb') && userLogin ) {
    const MB_MainAccountComponent = lazy(()=> import('./components/mobile/MainAccountComponent'));
    ReactDom.render(
        <Suspense fallback={<div>.</div>}>
            <MB_MainAccountComponent />
        </Suspense>, document.getElementById('account-page-mb'));
} else {
    let element = null;
    if( document.getElementById('user-not-login') ) {
        element = document.getElementById('user-not-login');
    }
    else if( document.getElementById('mb-nav-user') ) {
        element = document.getElementById('mb-nav-user');
    }
    if( element ) {
        const MainMBFormComponent = lazy(()=> import('./components/mobile/MainMBFormComponent'));
        ReactDom.render(
            <Suspense fallback={<div>.</div>}>
                <MainMBFormComponent />
            </Suspense>, element);
    }
    
}
// if( typeof user_login == 'undefined' || !user_login ) {
    
    
// } else {
    
// }
