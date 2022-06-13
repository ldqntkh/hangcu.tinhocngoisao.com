import React, { lazy } from 'react';
const hangcu_home_ajax = '/wp-admin/admin-ajax.php';
// import FacebookLogin from 'react-facebook-login';

// import LoginFormComponent from '../share/account/LoginFormComponent';
// import RegisterFormComponent from '../share/account/RegisterFormComponent';
// import ForgotPasswordComponent from '../share/account/ForgotPasswordComponent';

const LoginFormComponent = lazy(()=> import('../share/account/LoginFormComponent'));
const RegisterFormComponent = lazy(()=> import('../share/account/RegisterFormComponent'));
const ForgotPasswordComponent = lazy(()=> import('../share/account/ForgotPasswordComponent'));

// import FacebookLoginComponent from '../share/social/FacebookLoginComponent';
import SocialButton from '../share/social/SocialButton';
import axios from 'axios';
class MainFormComponent extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            showPopup : false,
            actionType: 'login',
            redirectUrl: '',
            otpForm: false,
            showForgotForm : false,
            showLoginOldGg: false,
            showLoginOldFb: false,
            user: null
        }
        window.mainComponentLoginAccount = this;
        this.childRegisterForm = React.createRef();
        this.nodes = {}
    }

    async componentDidMount() {
        await this._checkUserLogged();
        let user_data = sessionStorage.getItem('user');
        if( user_data ) {
            let user = JSON.parse(user_data);
            if( user.data && user.data.ID ) {
                this.setState({ user, showPopup: false });
            } else if( user.data === false ) {
                // keep data
                this.setState({ user: null });
            } else {
                sessionStorage.removeItem('user');
                this.setState({ user: null });
            }
        }
    }

    _checkUserLogged = async ()=> {
        let skeys = Object.keys(sessionStorage);
        let flag = false;
        for( let i = 0; i < skeys.length; i++ ) {
            if( skeys[i].indexOf('wc_fragments_') == 0 ) {
            // lấy data
            let fragments = sessionStorage.getItem( fragment_name );
            if( fragments ) {
                fragments = JSON.parse(fragments);
                $.each( fragments, function( key, value ) {
                $( key ).replaceWith( value );
                });
            } else {
                window.has_fragment_refresh = jQuery.ajax(window.fragment_refresh);
            }
            flag = true;
            break;
            }
        }
        if( !flag ) {
            window.has_fragment_refresh = jQuery.ajax(window.fragment_refresh);
        }
        
        let user_data = sessionStorage.getItem('user');
        if( user_data == null ) {
            try {
                var data = new FormData();
                data.append('action', 'check_user_logged');
                let response = await axios.post(
                    hangcu_home_ajax,
                    data
                );
                
                let responseData = response.data.data || response.data;
                if( responseData.success ) {
                    
                    let dataUser = responseData.user;
                    if( !dataUser ) {
                        // hầu như ko xảy ra
                        this._setUserLogin(null);
                    } else {
                        this._setUserLogin(dataUser);
                    }
                } else {
                    this._setUserLogin(false);
                }
            } catch (err) {
                this._setUserLogin(null);
            } finally {
                document.body.classList.remove('hangcu_loading');
            }
        }
        
    }

    setActionShowPopupOutSide = (url)=> {
        this.setState({
            showPopup: true,
            redirectUrl: url
        })
    }

    setOtpForm = (status = false) => {
        this.setState({
            otpForm: status
        });
    }

    _setUserLogin = (user) => {
        if( user && user.data ) {
            sessionStorage.setItem('user', JSON.stringify(user));
            this.setState({ user, showPopup: false });
            if( this.state.redirectUrl ) {
                if( this.state.redirectUrl.indexOf('#reviews') > 0 ) {
                    window.location.href = this.state.redirectUrl;
                } else {
                    window.location.href = this.state.redirectUrl.replace('#', '') + '/?t=' + new Date().getTime();
                }
            } else if(location.href.indexOf( 'redirect_to=' ) > 0) {
                let url = location.href.split('redirect_to=');
                url = url[1];
                
                window.location.href = url.replace('#', '') + '/?t=' + new Date().getTime();
            }
        } else if(user === false) {
            sessionStorage.setItem('user', JSON.stringify({ data: false }));
            this.setState({ user: null });
        } else {
            sessionStorage.removeItem('user');
            this.setState({ user });
        }
        
    };

    _backToRegisterForm = ()=> {
        this.setState({ otpForm: false });
        this.childRegisterForm.current._setSendOtpForm(false);
    }

    _showFormAccount = ()=> {
        let {
            actionType,
            redirectUrl,
            otpForm,
            showLoginOldFb,
            showLoginOldGg
        } = this.state;
        return(
            <div id="popup-account">
                <div className="form-account">
                    <span id="close-login-popup" className="electro-close-icon" onClick={this._closePopup}></span>
                    {/* <div className={`header ${actionType == 'register' ? 'active' : ''}`}>
                        <a href="#" id="form-account-login" >Đăng nhập</a>
                        <a href="#" id="form-account-register" >Tạo tài khoản</a>
                        
                    </div> */} 
                    <div className="body-content">
                        <div className="form-content">
                            <div className="form-controls">
                                <div className="heading-form">
                                    {
                                        !otpForm ?
                                        <React.Fragment>
                                            <h4>Xin chào,</h4>
                                            {
                                                actionType == 'register' ?
                                                <p><span onClick={()=> this.setState({ showPopup: true, actionType: 'login' })}>Đăng nhập</span> hoặc Tạo tài khoản</p> 
                                                :
                                                <p>Đăng nhập hoặc <span onClick={()=> this.setState({ showPopup: true, actionType: 'register' })}>Tạo tài khoản</span></p>
                                            }
                                        </React.Fragment>
                                        :
                                        <React.Fragment>
                                            <h4>Đăng ký tài khoản</h4>
                                            <p><span onClick={this._backToRegisterForm}>Quay lại</span></p> 
                                        </React.Fragment>
                                    }
                                    
                                </div>
                                {
                                    actionType == 'login' ?
                                    <LoginFormComponent 
                                        showLoginOldFb={showLoginOldFb}
                                        showLoginOldGg={showLoginOldGg}
                                        redirectUrl={redirectUrl} 
                                        handleSocialLogin={this.handleSocialLogin}
                                        _showFormForgotPassword={this._showFormForgotPassword} 
                                        _setUserLogin={this._setUserLogin}/>
                                    :
                                    <RegisterFormComponent 
                                        ref={this.childRegisterForm} 
                                        redirectUrl={redirectUrl} 
                                        _setUserLogin={this._setUserLogin}
                                        setOtpForm={this.setOtpForm}/>
                                }
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }

    _closePopup = ()=> {
        this.setState({
            showForgotForm: false,
            showPopup: false,
            actionType: 'login',
            otpForm: false,
            redirectUrl: ''
        })
    }

    _showFormForgot = ()=> {
        let {
            
        } = this.state;
        return(
            <div id="popup-account">
                <div className="form-account">
                    <span id="close-login-popup" className="electro-close-icon" onClick={this._closePopup}></span>
                    
                    <div className="body-content">
                        <div className="form-content">
                            <div className="form-controls">
                                <div className="heading-form">
                                    <p><span onClick={()=> this._showFormForgotPassword(false)}>Quay lại</span></p> 
                                </div>
                                <ForgotPasswordComponent />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
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
                let responseData = responseRegister.data.data || responseRegister.data;
                if( responseData.success ) {
                    let dataUser = responseData.user;
                    if( !dataUser ) {
                        if( this.props.redirectUrl ) {
                            if( this.state.redirectUrl.indexOf('#reviews') > 0 ) {
                                window.location.href = this.state.redirectUrl;
                            } else {
                                window.location.href = this.state.redirectUrl.replace('#', '') + '/?t=' + new Date().getTime();
                            }
                        } else if(location.href.indexOf( 'redirect_to=' ) > 0) {
                            let url = location.href.split('redirect_to=');
                            url = url[1];
                            window.location.href = url.replace('#', '') + '/?t=' + new Date().getTime();
                        } else {
                            window.location.href = window.location.href.replace('#', '') + '/?t=' + new Date().getTime();
                        }
                    } else {
                        this._setUserLogin(dataUser);
                    }
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
                if( responseData.success ) {
                    let dataUser = responseData.user;
                    if( !dataUser ) {
                        if( this.props.redirectUrl ) {
                            if( this.state.redirectUrl.indexOf('#reviews') > 0 ) {
                                window.location.href = this.state.redirectUrl;
                            } else {
                                window.location.href = this.state.redirectUrl.replace('#', '') + '/?t=' + new Date().getTime();
                            }
                        } else if(location.href.indexOf( 'redirect_to=' ) > 0) {
                            let url = location.href.split('redirect_to=');
                            url = url[1];
                            window.location.href = url.replace('#', '') + '/?t=' + new Date().getTime();
                        } else {
                            window.location.href = window.location.href.replace('#', '') + '/?t=' + new Date().getTime();
                        }
                    } else {
                        this._setUserLogin(dataUser);
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

    
    _showToltipActions = ()=> {
        let {
            showPopup,
            showForgotForm,
            showLoginOldGg,
            showLoginOldFb,
            user
        } = this.state;
        return( 
            <React.Fragment>
                {
                    user && user.data ?
                    <a href='/tai-khoan'>
                        <i className="ec ec-user"></i>
                        <span>
                            <span>{ user.data.display_name ? user.data.display_name : user.data.user_nicename }</span><br/>
                            <span className="icon-down">Tài khoản</span>
                        </span>
                    </a>
                    :

                    <aside>
                        <i className="ec ec-user"></i>
                        <span>
                            <span>Đăng nhập / Đăng ký</span><br/>
                            <span className="icon-down">Tài khoản<i className="arrow-down"></i></span>
                        </span>
                    </aside>
                }

                {
                    // !user && !showPopup && !showForgotForm && 
                    // <div className="popup-navbar-account minimum">
                    //     <a id="login-account" href="#"  data-title="login" onClick={()=> this.setState({ showPopup: true, actionType: 'login' })}>Đăng nhập</a>
                    //     <a id="register-account" href="#" data-title="register" onClick={()=> this.setState({ showPopup: true, actionType: 'register' })}>Tạo tài khoản</a>
                    // </div>
                    // show popup action user
                }
            </React.Fragment>
            
        )
    }

    _showFormForgotPassword = (status = true)=> {
        this.setState({
            showForgotForm: status,
            showPopup: !status
        })
    }

    render() {

        let {
            showPopup,
            showForgotForm
        } = this.state;

        return(
            <React.Fragment>
            {
                showPopup && this._showFormAccount()
            }
            {
                showForgotForm && this._showFormForgot()
            }
            {
                this._showToltipActions()
            }
            </React.Fragment>
        )
    }
}

export default MainFormComponent;