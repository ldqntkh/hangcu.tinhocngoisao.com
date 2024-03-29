import React, { lazy, Suspense } from 'react';
import ReactDom from 'react-dom';
// import MainFormComponent from './components/desktop/MainFormComponent';
// import DK_MainAccountComponent from './components/desktop/MainAccountComponent';

// check user login ?


if( document.getElementById('user-not-login') ) {
    let element = document.getElementById('user-not-login');
    const MainMBFormComponent = lazy(()=> import('./components/mobile/MainMBFormComponent'));
    ReactDom.render(
        <Suspense fallback={<div>.</div>}>
            <MainMBFormComponent />
        </Suspense>, element);
} else {
    if( document.getElementById('user-account') && !document.getElementById('account-page') ) {
        const MainFormComponent = lazy(()=> import('./components/desktop/MainFormComponent'));
    
        ReactDom.render(
            <Suspense fallback={<div>.</div>}>
                <MainFormComponent />
            </Suspense>
            , document.getElementById('user-account'));
    } else {
       
        if (sessionStorage.getItem('user') && !JSON.parse(sessionStorage.getItem('user')).data) {
            sessionStorage.removeItem('user');
            window.location.href = window.location.origin + '?t=' + new Date().getTime();
        } else {
            if( document.getElementById('account-page') && sessionStorage.getItem('user') && JSON.parse(sessionStorage.getItem('user')).data ) {
                const DK_MainAccountComponent = lazy(()=> import('./components/desktop/MainAccountComponent'));
                ReactDom.render(
                    <Suspense fallback={<div>.</div>}>
                        <DK_MainAccountComponent />
                    </Suspense>
                , document.getElementById('account-page'));
            } 
        }
    }
}


   