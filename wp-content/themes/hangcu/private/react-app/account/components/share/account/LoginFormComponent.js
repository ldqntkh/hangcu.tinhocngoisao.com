import React from 'react';
const hangcu_home_ajax = '/wp-admin/admin-ajax.php';
import axios from 'axios';
import FormData from 'form-data';
import SocialButton from '../../share/social/SocialButton';
class LoginFormComponent extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            username: '',
            password: '',
            error_username: '',
            error_password: '',
            error: '',
            showLoginOldGg: false,
            showLoginOldFb: false
        }
    }

    componentDidMount() {
        if(this.props.showLoginOldFb) {
            this.setState({showLoginOldFb: this.props.showLoginOldFb});
        }
        if(this.props.showLoginOldGg) {
            this.setState({showLoginOldGg: this.props.showLoginOldGg});
        }
    }


    _handleChangeInput = (event)=> {
        let data = this.state;
        data['error'] = '';
        data[event.target.name] = event.target.value;
        
        if( event.target.name == 'username' ) {
            let msg = '';
            if( event.target.value.trim() == '' ) {
                msg = 'Vui lòng nhập email hoặc số điện thoại'
            } else if( event.target.value.trim().length < 6 ) {
                msg = 'Thông tin đăng nhập phải có ít nhất 6 ký tự'
            }
            data.error_username = msg;
        } else {
            let msg = '';
            if( event.target.value.trim() == '' ) {
                msg = 'Vui lòng nhập mật khẩu'
            } else if( event.target.value.trim().length < 6 ) {
                msg = 'Mật khẩu phải có ít nhất 6 ký tự'
            }
            data.error_password = msg;
        }
        this.setState(data)
    }

    _loginAccount = async()=> {
        let {
            username,
            error_username,
            password,
            error_password
        } = this.state;
        if( error_username != '' || error_password != '' ) return false;
        if( username == '' || password == '' ) return false;
        document.body.classList.add('hangcu_loading');
        try {
            var data = new FormData();
            data.append('action', 'login_account');
            data.append('username', username);
            data.append('password', password);

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
                    error: responseData.errMsg
                });
            }
            window.has_fragment_refresh = jQuery.ajax(window.fragment_refresh);
        } catch (err) {
            this.setState({
                error: err.message
            })
        } finally {
            document.body.classList.remove('hangcu_loading');
        }
    }

    handleSocialLogin = async (user) => {
        let profile = user._profile;
        let token = user._token;
        
        try {
            document.body.classList.add('hangcu_loading');
            let provider = 'facebook';
            if( user._provider ) provider = user._provider;

            let url = '';
            if( provider == 'facebook' ) {
                url = '/wp-json/nextend-social-login/v1/facebook/get_user?access_token={"access_token":"<token>","token_type":"bearer","expires_in":<expires>}';
                url = url.replace('<token>',token.accessToken ).replace('<expires>', token.expiresAt);
            } else {
                url = '/wp-json/nextend-social-login/v1/google/get_user?access_token={"access_token":"<token>","expires_in":<expires>,"id_token":"<id_token>","token_type":"Bearer"}';
                url = url.replace('<token>',token.accessToken ).replace('<expires>', token.expiresIn).replace('<id_token>', token.idToken);
            }

            
            // access.access_token = response.accessToken;
            // access.expires_in = response.data_access_expiration_time;
            
            let _response = await axios.post(url);
            
            if( _response.data == "" || _response.data === "0" ) {
                // create new account

                var data = new FormData();
                data.append('action', 'register_social_account');
                data.append('user_id', profile.id); 
                data.append('user_name', profile.name); 
                data.append('user_email', profile.email); 
                data.append('providerID', provider);
                let responseRegister = await axios.post(
                    hangcu_home_ajax,
                    data
                );
                let responseData = responseRegister.data;
                if( responseData.success ) {
                    sessionStorage.clear();
                    // return window.location.href.replace('#', '') + '/?t=' + new Date().getTime();
                    window.location.href = location.origin + location.pathname + '/?t=' + new Date().getTime();
                } else {
                    alert(responseData.errMsg);
                    return false;
                }
            } else {
                // login account with id
                let id = _response.data;
                var data = new FormData();
                data.append('action', 'login_account_byid');
                data.append('user_id', id);

                let response = await axios.post(
                    hangcu_home_ajax,
                    data
                );
                let responseData = response.data.data || response.data;
                if( response.data.success ) {
                    // return window.location.href.replace('#', '') + '/?t=' + new Date().getTime();
                    // window.location.href = location.origin + location.pathname + '/?t=' + new Date().getTime();
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
                        window.has_fragment_refresh = jQuery.ajax(window.fragment_refresh);
                    }
                    
                } else {
                    alert(responseData.errMsg);
                    return false;
                }
            }

        } catch (err) {
            console.log(err);
            return false;
        } finally {
            document.body.classList.remove('hangcu_loading');
            // window.fbAsyncInit = function() {
            // window.FB.init({
            //     appId      : '296938078697056',
            //     cookie     : true,                     // Enable cookies to allow the server to access the session.
            //     xfbml      : true,                     // Parse social plugins on this webpage.
            //     version    : 'v11.0'           // Use this Graph API version for this call.
            // });}
            // window.FB.api('/me/permissions', 'delete', null, () => window.FB.logout());
        }
    };
    
    handleSocialFBLoginFailure = (err) => {
        // window.FB.api('/me/permissions', 'delete', null, () => window.FB.logout());
        // console.log(err);
        this.setState({
            showLoginOldFb: true
        })
    };
    
    handleSocialGGLoginFailure = (err) => {
        // window.FB.api('/me/permissions', 'delete', null, () => window.FB.logout());
        // console.log(err);
        this.setState({
            showLoginOldGg: true
        })
    };

    _handleKeyDown = (e)=> {
        if (e.key === 'Enter') {
            this._loginAccount();
        }
    }

    render() {
        let {
            username, 
            error_username,
            password,
            error_password,
            error,
            showLoginOldFb,
            showLoginOldGg
        } = this.state;
        let redirect_url = this.props.redirectUrl ? this.props.redirectUrl : home_page + `/?t=_${new Date().getTime()}`;
        // href={`${login_account_url}?loginSocial=facebook&amp;redirect=${redirect_url}`} 
        return(
            <React.Fragment>
                <form autoComplete="off" onSubmit={e => { e.preventDefault(); }}>
                    <div className={`input ${error_username != '' ? 'error' : ''}`}>
                        <input value={username} name="username" type="text" placeholder="Nhập Email hoặc Số điện thoại " onChange={this._handleChangeInput}
                            onKeyDown={this._handleKeyDown}/>
                    </div>
                    {
                        error_username &&
                        <p className="error">{error_username}</p>
                    }
                    <div className={`input ${error_password != '' ? 'error' : ''}`} >
                        <input value={password} name="password" type="password" placeholder="Nhập Mật khẩu " onChange={this._handleChangeInput}
                            onKeyDown={this._handleKeyDown}/>
                    </div>
                    {
                        error_password &&
                        <p className="error">{error_password}</p>
                    }
                    <div style={{marginTop: 20, textAlign: 'right'}}>
                        <a href="#" onClick={()=>this.props._showFormForgotPassword(true)}>Quên mật khẩu?</a>
                    </div>
                    {
                        error &&
                        <p className="error">{error}</p>
                    }
                    <div style={{marginTop: 20, textAlign: 'center'}}>
                        <button onClick={this._loginAccount} type="button" className="btn-login">Đăng nhập</button>
                    </div>
                </form>
                
            </React.Fragment>
        )
    }
}

export default LoginFormComponent;