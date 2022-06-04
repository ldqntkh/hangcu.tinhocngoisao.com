import React from 'react';
import {
    Link
} from "react-router-dom";
import axios from 'axios';
import FormData from 'form-data';
import { hangcu_home_ajax } from '../variable/variables';
import StickyComponent from './StickyComponent';

class NavbarComponent extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            pathname : ''
        }
    }

    componentDidMount() {
        let pathname = location.pathname;
        if( pathname[pathname.length-1] == '/' ) {
            pathname = pathname.substring(0, pathname.length-1);
        }

        this.setState({
            pathname
        })
    }

    _logout = async()=> {
        try {
            document.body.classList.add('hangcu_loading');

            var data = new FormData();
            
            data.append('action', 'hc_logout_account');
            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            sessionStorage.clear()
        } catch (err) {
            console.log(err);
        } finally {
            window.location.href = window.location.origin;
        }
    }
    

    render() {
        let pathname = this.state.pathname;

        return (
            <>
                <StickyComponent title="Tài khoản" backLink="/" />
                <div className="left-navbar">
                    <Link onClick={()=> this.setState({ pathname: '/tai-khoan/edit-account' })} className={ pathname.indexOf('/tai-khoan/edit-account') == 0 || pathname == '/tai-khoan'  ? 'active' : ''} to="/tai-khoan/edit-account">
                        <i className="ac-icon icon-user"></i>
                        { userLogin.data.display_name ? userLogin.data.display_name : "Thông tin tài khoản" }  
                        <i className="fa fa-angle-right"></i>
                    </Link>
                    <Link onClick={()=> this.setState({ pathname: '/tai-khoan/orders' })} className={ pathname.indexOf('/tai-khoan/orders') == 0 || pathname.indexOf('/tai-khoan/view-order/') == 0 ? 'active' : '' } to="/tai-khoan/orders/">
                        <i className="ac-icon icon-order"></i>
                        Quản lý đơn hàng
                        <i className="fa fa-angle-right"></i>
                    </Link>
                    <Link onClick={()=> this.setState({ pathname: '/tai-khoan/edit-address' })} className={ pathname.indexOf('/tai-khoan/edit-address') == 0 ? 'active' : '' } to="/tai-khoan/edit-address">
                        <i className="ac-icon icon-address"></i>
                        Sổ địa chỉ
                        <i className="fa fa-angle-right"></i>
                    </Link>
                    <a href="#" onClick={this._logout}>
                        <i className="ac-icon icon-logout"></i>
                        Đăng xuất
                    </a>
                </div>
            </>
        );
    }
}

export default NavbarComponent;