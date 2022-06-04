import React from 'react';
const hangcu_home_ajax = '/wp-admin/admin-ajax.php';
import axios from 'axios';
class FacebookLoginComponent extends React.Component {

    componentDidMount() {
        document.addEventListener('FBObjectReady', this.initializeFacebookLogin);
    }
    
    componentWillUnmount() {
        document.removeEventListener('FBObjectReady', this.initializeFacebookLogin);
    }
    
    /**
     * Init FB object and check Facebook Login status
     */
    initializeFacebookLogin = () => {
        this.FB = window.FB;
        this.checkLoginStatus();
    }
    
    /**
     * Check login status
     */
    checkLoginStatus = () => {
        this.FB.getLoginStatus(this.facebookLoginHandler);
    }

    responseFacebook = async response => {
        if( response.accessToken ) {
            try {
                document.body.classList.add('hangcu_loading');
                let url = '/wp-json/nextend-social-login/v1/facebook/get_user?access_token={"access_token":"<token>","token_type":"bearer","expires_in":<expires>}'
                url = url.replace('<token>',response.accessToken ).replace('<expires>', response.data_access_expiration_time);
                // access.access_token = response.accessToken;
                // access.expires_in = response.data_access_expiration_time;
                
                let _response = await axios.post(url);
                
                if( _response.data == "" ) {
                    // create new account
                    var data = new FormData();
                    data.append('action', 'register_social_account');
                    data.append('user_id', response.userID); 
                    data.append('user_name', response.name); 
                    data.append('user_email', response.email); 
                    data.append('providerID', 'facebook');
                    let responseRegister = await axios.post(
                        hangcu_home_ajax,
                        data
                    );
                    let responseData = responseRegister.data;
                    if( response.success ) {
                        return window.location.href.replace('#', '') + '/?t=' + new Date().getTime();
                        // window.location.href = window.location.href.replace('#', '') + '/?t=' + new Date().getTime();
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
                        return window.location.href.replace('#', '') + '/?t=' + new Date().getTime();
                        // window.location.href = window.location.href.replace('#', '') + '/?t=' + new Date().getTime();
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
        }
    }
    
    /**
     * Check login status and call login api is user is not logged in
     */
    facebookLogin = () => {
        if (!this.FB) return;
    
        this.FB.getLoginStatus(response => {
        if (response.status === 'connected') {
            this.responseFacebook(response);
        } else {
            this.FB.login(this.facebookLoginHandler, {scope: 'public_profile'});
        }
        }, );
    }
    
    /**
     * Handle login response
     */
    facebookLoginHandler = response => {
        if (response.status === 'connected') {
            this.FB.api('/me/permissions', 'delete', null, () => window.FB.logout());
        } 
    }
    
    render() {
        let {children} = this.props;
        return (
        <div className="nsl-container nsl-container-block" data-align="left" onClick={this.facebookLogin}>
            {children}
        </div>
        );
    }
}


export default FacebookLoginComponent;