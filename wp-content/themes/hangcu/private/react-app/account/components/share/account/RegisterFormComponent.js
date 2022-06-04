import React from 'react';
const hangcu_home_ajax = '/wp-admin/admin-ajax.php';
import axios from 'axios';
import FormData from 'form-data';

class RegisterFormComponent extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            full_name: '',
            error_full_name: '',
            phone_number: '',
            error_phone_number: '',
            email: '',
            error_email: '',
            password: '',
            error_password: '',
            re_password: '',
            error_re_password: '',
            sendOtp: false,
            otpCode: '',
            error_otpCode: '',
            error: ''
        }
    }

    _handleChangeInput = (event)=> {
        let data = this.state;
        data['error'] = '';
        data[event.target.name] = event.target.value;
        
        if( event.target.name == 'full_name' ) {
            let msg = '';
            if( event.target.value.trim() == '' ) {
                msg = 'Vui lòng nhập họ tên'
            }
            data.error_full_name = msg;
        } else if( event.target.name == 'phone_number' ) {
            let msg = '';
            if( event.target.value.trim() == '' ) {
                msg = 'Vui lòng nhập số điện thoại'
            } else {
                let patt = new RegExp("(09|03|07|08|05)+([0-9]{8}$)");
                if( !patt.test( event.target.value.trim() ) ) {
                    msg = 'Số điện thoại không hợp lệ'
                }
            }
            data.error_phone_number = msg;
        } else if( event.target.name == 'email' ) {
            let msg = '';
            if( event.target.value.trim() == '' ) {
                msg = 'Vui lòng nhập email'
            } else {
                let re_email = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if( !re_email.test(event.target.value.trim()) ) {
                    msg = 'Email không hợp lệ'
                }
            }
            data.error_email = msg;
        } else if( event.target.name == 'password' ) {
            let msg = '';
            if( event.target.value.trim() == '' ) {
                msg = 'Vui lòng nhập mật khẩu'
            } else if( event.target.value.trim().length < 6 ) {
                msg = 'Mật khẩu phải có ít nhất 6 ký tự'
            }
            data.error_password = msg;
        } else if( event.target.name == 're_password' ) {
            let msg = '';
            if( event.target.value.trim() != data.password ) {
                msg = 'Mật khẩu không giống nhau'
            }
            data.error_re_password = msg;
        }

        this.setState(data)
    }

    _sendOtp = async(status = false)=> {
        let {
            phone_number,
            full_name,
            email,
            password,
            re_password
        } = this.state;
        let dataState = this.state;

        let hasErr = false;
        let re_email = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if( full_name.trim() == '' ) {
            dataState.error_full_name = 'Vui lòng nhập họ tên';
            hasErr = true;
        } else if( phone_number.trim() == '' ) {
            dataState.error_phone_number = 'Vui lòng nhập số điện thoại';
            hasErr = true;
        } else if( !new RegExp("(09|03|07|08|05)+([0-9]{8}$)").test(phone_number.trim()) ) {
            dataState.error_phone_number = 'Số điện thoại không hợp lệ';
            hasErr = true;
        } else if( email.trim() == '' ) {
            dataState.error_email = 'Vui lòng nhập email';
            hasErr = true;
        } else if( !re_email.test(email.trim()) ) {
            dataState.error_email = 'Email không hợp lệ';
            hasErr = true;
        } else if( password.trim() == '' ) {
            dataState.error_password = 'Vui lòng nhập mật khẩu';
            hasErr = true;
        } else if( password.trim().length < 6 ) {
            dataState.error_password = 'Mật khẩu phải có ít nhất 6 ký tự';
            hasErr = true;
        } else if( password.trim() != re_password.trim() ) {
            dataState.error_re_password = 'Mật khẩu không giống nhau';
            hasErr = true;
        }

        if( hasErr ) {
            this.setState(dataState);
            return false;
        }

        if(status) {
            this.setState({
                sendOtp: true
            });
            this.props.setOtpForm(true);
            return false;
        }
        document.body.classList.add('hangcu_loading');
        try {
            var data = new FormData();
            data.append('action', 'createotpcode');
            data.append('phone_number', phone_number.trim());

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            let responseData = response.data.data || response.data;
            if( response.data.success ) {
                this.setState({
                    sendOtp: true
                });
                this.props.setOtpForm(true);
            } else {
                this.setState({
                    error_phone_number: responseData.errMsg,
                    sendOtp: false
                });
            }
        } catch (err) {
            this.setState({
                error: err.message,
                sendOtp: false
            });
        } finally {
            document.body.classList.remove('hangcu_loading');
        }
    }

    _setSendOtpForm = (status = false)=> {
        this.setState({
            sendOtp: status,
            error_otpCode: ''
        })
    }

    _registerAccount = async()=> {
        let {
            full_name,
            error_full_name,
            phone_number,
            error_phone_number,
            email,
            error_email,
            password,
            error_password,
            otpCode,
            error
        } = this.state;

        if( !otpCode.trim() && otpCode.trim() != 6 ) {
            this.setState({
                error_otpCode: "Mã OTP không hợp lệ"
            })
            return false;
        }

        if( error_full_name != '' || error_phone_number != '' || error_email != '' ||
                error_password != '' || error != '' ) {
            return false;
        }

        document.body.classList.add('hangcu_loading');
        try {
            var data = new FormData();
            data.append('action', 'register_account');
            data.append('data_user[fullname]', full_name.trim());
            data.append('data_user[phonenumber]', phone_number.trim());
            data.append('data_user[verify_phone]', otpCode.trim());
            data.append('data_user[email]', email.trim());
            data.append('data_user[password]', password.trim());

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            let responseData = response.data.data || response.data;
            if( responseData.success ) {
                let dataUser = responseData.user;
                if( !dataUser ) {
                    if( this.props.redirectUrl ) {
                        if( this.props.redirectUrl.indexOf('#reviews') > 0 ) {
                            window.location.href = this.props.redirectUrl;
                        } else {
                            window.location.href = this.props.redirectUrl.replace('#', '') + '/?t=' + new Date().getTime();
                        }
                    } else if(location.href.indexOf( 'redirect_to=' ) > 0) {
                        let url = location.href.split('redirect_to=');
                        url = url[1];
                        window.location.href = url.replace('#', '') + '/?t=' + new Date().getTime();
                    } else {
                        window.location.href = window.location.href.replace('#', '') + '/?t=' + new Date().getTime();
                    }
                } else {
                    this.props._setUserLogin(dataUser);
                }
                
            } else {
                this.setState({
                    error_otpCode: responseData.errMsg
                });
            }
        } catch (err) {
            this.setState({
                error_otpCode: err.message
            })
        } finally {
            document.body.classList.remove('hangcu_loading');
        }
    }

    render() {
        let {
            full_name,
            error_full_name,
            phone_number,
            error_phone_number,
            email,
            error_email,
            password,
            error_password,
            re_password,
            error_re_password,
            error,

            otpCode,
            error_otpCode,
            sendOtp
        } = this.state;
        return(
            <form autoComplete="off" onSubmit={e => { e.preventDefault(); }} className="register">
                {
                    !sendOtp ?
                    <React.Fragment>
                        <div className={`input ${error_full_name != '' ? 'error' : ''}`}>
                            <input value={full_name} name="full_name" type="text" placeholder="Nhập họ tên" onChange={this._handleChangeInput}/>
                        </div>
                        {
                            error_full_name &&
                            <p className="error">{error_full_name}</p>
                        }

                        <div className={`input ${error_phone_number != '' ? 'error' : ''}`}>
                            <input value={phone_number} name="phone_number" type="text" placeholder="Nhập số điện thoại" onChange={this._handleChangeInput}/>
                        </div>
                        {
                            error_phone_number &&
                            <p className="error">{error_phone_number}</p>
                        }

                        <div className={`input ${error_email != '' ? 'error' : ''}`}>
                            <input value={email} name="email" type="email" placeholder="Nhập email" onChange={this._handleChangeInput}/>
                        </div>
                        {
                            error_email &&
                            <p className="error">{error_email}</p>
                        }

                        <div className={`input ${error_password != '' ? 'error' : ''}`} >
                            <input value={password} name="password" type="password" placeholder="Nhập mật khẩu " onChange={this._handleChangeInput}/>
                        </div>
                        {
                            error_password &&
                            <p className="error">{error_password}</p>
                        }

                        <div className={`input ${error_re_password != '' ? 'error' : ''}`} >
                            <input value={re_password} name="re_password" type="password" placeholder="Nhập lại mật khẩu " onChange={this._handleChangeInput}/>
                        </div>
                        {
                            error_re_password &&
                            <p className="error">{error_re_password}</p>
                        }
                        
                        {
                            error &&
                            <p className="error">{error}</p>
                        }
                        <div style={{marginTop: 20, textAlign: 'right'}}>
                            <a href="#" onClick={()=>this._sendOtp(true)}>Đã có mã OTP?</a>
                        </div>
                        <div style={{marginTop: 20}}>
                            <button onClick={()=>this._sendOtp(false)} type="button" className="btn-login">Xác minh số điện thoại</button>
                        </div>  
                    </React.Fragment>
                    :
                    <React.Fragment>
                        <p>Một tin nhắn chứa mã OTP đã được gửi đến số điện thoại: {phone_number}.
                        <br/>Vui lòng nhập mã OTP vào ô bên dưới</p>
                        <div className={`input ${error_otpCode != '' ? 'error' : ''}`}>
                            <input value={otpCode} name="otpCode" type="text" placeholder="Mã OTP" onChange={this._handleChangeInput}/>
                        </div>
                        {
                            error_otpCode &&
                            <p className="error">{error_otpCode}</p>
                        }

                        <div style={{marginTop: 20}}>
                            <button onClick={this._registerAccount} type="button" className="btn-login">Đăng ký tài khoản</button>
                        </div>  
                    </React.Fragment>
                }
                
            </form>
        )
    }
}

export default RegisterFormComponent;