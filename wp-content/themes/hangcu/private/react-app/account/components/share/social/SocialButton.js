import React from "react";
import SocialLogin from "react-social-login";

class SocialButton extends React.Component {
  render() {
    const { children, triggerLogin, ...props } = this.props;
    return (
        <div className="nsl-container nsl-container-block" data-align="left" onClick={triggerLogin} {...props}>
            {children}
        </div>
    );
  }
}

export default SocialLogin(SocialButton);