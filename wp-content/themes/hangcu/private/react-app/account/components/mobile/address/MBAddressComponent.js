import React from 'react';

import StickyComponent from '../StickyComponent';
import ListAddressComponent from '../../share/address/ListAddressComponent';

class MBAddressComponent extends React.Component {

    render() {
        return(
            <>
                <StickyComponent {...this.props} title="Sổ địa chỉ" backLink="/tai-khoan/"/>
                <ListAddressComponent {...this.props}/>
            </>
        )
    }
}

export default MBAddressComponent;