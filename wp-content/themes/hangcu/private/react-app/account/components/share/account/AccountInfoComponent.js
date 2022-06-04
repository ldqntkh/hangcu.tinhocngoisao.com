import React from 'react';
import axios from 'axios';
import FormData from 'form-data';
import { hangcu_home_ajax } from '../../variable/variables';
class AccountInfoComponent extends React.Component {

    constructor( props ) {
        super(  props );
        this.state = {
            user_id: '',
            display_name: '',
            user_email: '',
            user_login: '',
            phone_number: '',
            is_change_pass: false,
            err_display_name: '',

            current_pass: '',
            new_pass: '',
            re_new_pass: '',
            errPass: ''
        }
    }

    componentDidMount() {
        if( userLogin ) {
            this.setState({
                user_id: userLogin.data.ID,
                display_name: userLogin.data.display_name ? userLogin.data.display_name : user_nicename,
                user_email: userLogin.data.user_email,
                user_login: userLogin.data.user_login,
                phone_number: userLogin.data.phone_number
            })
        }
    }

    _handleInputChange = (e)=> {
        let data = this.state;
        data[e.target.name] = e.target.value;

        this.setState(data);
    }

    _updateAccountDetail = async() => {
        let {
            display_name,
            is_change_pass,
            current_pass,
            new_pass,
            re_new_pass
        } = this.state;

        this.setState({
            err_display_name: '',
            errPass: ''
        })

        if( display_name.trim() == '' ) {
            this.setState({
                err_display_name: 'Vui lòng cung cấp Họ Tên!'
            });
            return false;
        }

        if( is_change_pass ) {
            if( current_pass.trim().length < 6 ) {
                this.setState({
                    errPass: 'Mật khẩu hiện tại không phù hợp'
                });
                return false;
            }
            if( new_pass.trim().length < 6 ) {
                this.setState({
                    errPass: 'Mật khẩu mới phải có ít nhất 6 kí tự'
                });
                return false;
            }
            if( new_pass.trim() != re_new_pass.trim() ) {
                this.setState({
                    errPass: 'Mật khẩu mới không giống nhau'
                });
                return false;
            }
        }

        let hasTrue = true;
        let changePassOk = false;
        try {
            document.body.classList.add('hangcu_loading');

            var data = new FormData();
            
            data.append('action', 'gvn_update_account_info');
            data.append('display_name', display_name);
            if( is_change_pass ) {
                data.append('current_pass', current_pass);
                data.append('new_pass', new_pass);
            }
           

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            
            if( response.data.success ) {
                let data = response.data.data;
                if( data.msg_dname != '' ) {
                    this.setState({
                        err_display_name: data.msg_dname
                    })
                } else {
                    let user = JSON.parse(sessionStorage.getItem('user'));
                    user.data.display_name = display_name;
                    sessionStorage.setItem('user', JSON.stringify(user));
                }
                if( data.msg_pass != '' ) {
                    this.setState({
                        errPass: data.msg_pass
                    })
                } else {
                    changePassOk = true;
                }
                
                hasTrue = true;
            } else {
                alert( response.data.data.msg );
                this.props.history.push('/tai-khoan');
                hasTrue = false;
            }

        } catch (err) {
            console.log(err);
        } finally {
            if( hasTrue ) {
                if( is_change_pass && changePassOk ) {
                    alert('Đổi mật khẩu thành công. Bạn sẽ được đưa về trang chủ!');
                    sessionStorage.clear();
                    location.href = '/'
                } else if( is_change_pass && !changePassOk ) {
                    document.body.classList.remove('hangcu_loading');
                } else {
                    location.reload(); 
                }
            }
        }
    }

    render() {
        let {
            display_name, user_email, user_login, phone_number, is_change_pass,
            current_pass, new_pass, re_new_pass,
            err_display_name, errPass
        } = this.state;
        return(
            <div className="account-info-container">
                <h3>Thông tin tài khoản</h3>
                <div className="form-container">
                    <div className="form-group">
                        <label>Tài khoản</label>
                        <input type="text" name="user_login" placeholder="Tài khoản" readOnly={true} value={user_login} onChange={()=> null } />
                    </div>
                    <div className="form-group">
                        <label htmlFor="display_name">Họ tên</label>
                        <input type="text" name="display_name" placeholder="Họ tên" value={display_name} onChange={this._handleInputChange} />
                    </div>
                    {
                        err_display_name && <p className="error">{err_display_name}</p>
                    }
                    <div className="form-group">
                        <label htmlFor="user_email">Email</label>
                        <input type="text"  name="user_email" placeholder="Email" value={user_email} readOnly={true} onChange={()=> null} />
                    </div>
                    <div className="form-group">
                        <label htmlFor="phone_number">Số điện thoại</label>
                        <input type="text"  name="phone_number" placeholder="Số điện thoại" value={phone_number} readOnly={true} onChange={()=> null} />
                    </div>
                    <div className="form-group checkbox" onClick={()=> this.setState({ is_change_pass: !is_change_pass })}>
                        <input type="checkbox" name="is_change_pass" checked={ is_change_pass } onChange={()=> null}></input>
                        <label htmlFor="is_change_pass">Đổi mật khẩu</label>
                    </div>
                    {
                        is_change_pass &&
                        <React.Fragment>
                            <div className="form-group">
                                <label htmlFor="current_pass">Mật khẩu hiện tại</label>
                                <input type="password"  name="current_pass" placeholder="Mật khẩu hiện tại" value={current_pass} onChange={this._handleInputChange} />
                            </div>
                            <div className="form-group">
                                <label htmlFor="new_pass">Mật khẩu mới</label>
                                <input type="password"  name="new_pass" placeholder="Mật khẩu mới" value={new_pass} onChange={this._handleInputChange} />
                            </div>
                            <div className="form-group">
                                <label htmlFor="re_new_pass">Nhập lại mật khẩu mới</label>
                                <input type="password"  name="re_new_pass" placeholder="Nhập lại mật khẩu mới" value={re_new_pass} onChange={this._handleInputChange} />
                            </div>
                            {
                                errPass && <p className="error">{errPass}</p>
                            }
                        </React.Fragment>
                    }
                    <div className="form-group">
                        <label>&nbsp;</label>
                        <button onClick={this._updateAccountDetail} type="button">Cập nhật</button>
                    </div>
                </div>
            </div>
        )
    }
}

export default AccountInfoComponent;