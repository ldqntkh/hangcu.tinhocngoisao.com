import React from 'react';
import ListOrderComponent from '../../share/order/ListOrderComponent';
import StickyComponent from '../StickyComponent';

class MBListOrderComponent extends React.Component {

    render() {
        return (
            <>
                <StickyComponent {...this.props} title="Đơn hàng của tôi" backLink="/tai-khoan/"/>
                <ListOrderComponent {...this.props}/>
            </>
        );
    }
}

export default MBListOrderComponent;