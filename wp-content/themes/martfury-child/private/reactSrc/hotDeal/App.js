import React from 'react';
import ReactDOM from 'react-dom';


import MainContainerMobile from './components/mainContainerMobile';
import MainContainerPC from './components/mainContainerPC';


const init = ()=> {
    try {
        if ( document.getElementById('dv-primetime-price-mobile') ) {
            ReactDOM.render(
                <MainContainerMobile />,
                document.getElementById('dv-primetime-price-mobile'));
        }
        
        if ( document.getElementById('dv-primetime-price-desktop') ) {
            ReactDOM.render(
                <MainContainerPC />,
                document.getElementById('dv-primetime-price-desktop'));
        }
        
    } catch(err) {
        console.log(err.message)
    }
}
export default init;