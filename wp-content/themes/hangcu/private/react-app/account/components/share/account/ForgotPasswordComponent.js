import React from 'react';
const hangcu_home_ajax = '/wp-admin/admin-ajax.php';
import axios from 'axios';
import FormData from 'form-data';

class ForgotPasswordComponent extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            username: '',
            error_msg: '',
            success_msg: ''
        };
    }

    _handleChangeInput = (event)=> {
        let data = this.state;
        data['success_msg'] = '';
        data[event.target.name] = event.target.value;
        
        let msg = '';
        if( event.target.value.trim() == '' ) {
            msg = 'Vui lòng nhập email hoặc số điện thoại'
        } else if( event.target.value.trim().length < 6 ) {
            msg = 'Thông tin đăng nhập phải có ít nhất 6 ký tự'
        }
        data.error_msg = msg;

        this.setState(data);
    }

    _getForgotPassword = async()=> {
        let {
            username, error_msg
        } = this.state;
        
        if( username == '' || error_msg != '' ) return false;

        document.body.classList.add('hangcu_loading');
        try {
            var data = new FormData();
            data.append('action', 'forgot_password');
            data.append('username', username);

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            let responseData = response.data.data || response.data;
            if( response.data.success ) {
                this.setState({
                    success_msg: responseData.errMsg
                });
            } else {
                this.setState({
                    error_msg: responseData.errMsg
                });
            }
        } catch (err) {
            this.setState({
                error_msg: err.message
            })
        } finally {
            document.body.classList.remove('hangcu_loading');
        }
    }

    render() {
        let {
            username, error_msg, success_msg
        } = this.state;
        if( success_msg == '' ) {
            return (
                <React.Fragment>
                    <form autoComplete="off" onSubmit={e => { e.preventDefault(); }}>
                        <h4>Quên mật khẩu?</h4>
                        <p>Vui lòng nhập thông tin tài khoản để lấy lại mật khẩu</p>
                        <div className={`input ${error_msg != '' ? 'error' : ''}`}>
                            <input value={username} name="username" type="text" placeholder="Nhập Email hoặc Số điện thoại " onChange={this._handleChangeInput}/>
                        </div>
                        {
                            error_msg &&
                            <p className="error">{error_msg}</p>
                        }
                        <div style={{marginTop: 20}}>
                            <button onClick={this._getForgotPassword} type="button" className="btn-login">Lấy lại mật khẩu</button>
                        </div>
                    </form>
                </React.Fragment>
            )
        } else {
           return(
                <React.Fragment>
                    <form autoComplete="off" onSubmit={e => { e.preventDefault(); }}>
                        <h4>Quên mật khẩu?</h4>
                        <p>{success_msg}</p>
                    </form>
                </React.Fragment>
           )
        }
        
    }
}

export default ForgotPasswordComponent;