import React from 'react';
import AccountInfoComponent from '../../share/account/AccountInfoComponent';
import StickyComponent from '../StickyComponent';

class MBAccountComponent extends React.Component {

    render() {
        return (
            <>
                <StickyComponent {...this.props} title="Thông tin tài khoản" backLink="/tai-khoan/"/>
                <AccountInfoComponent {...this.props}/>
            </>
        );
    }
}

export default MBAccountComponent;