import React from 'react';

class StickyComponent extends React.Component {

    constructor( props ) {
        super(props)
    }

    _goBack = ()=> {
        let backLink = this.props.backLink;
        window.scrollTo(0,0);
        if( backLink == '/' ) {
            location.href = backLink;
        } else if( this.props.history ) {
            this.props.history.push(backLink);
        } else {
            location.href = window.location.origin;
        }
    }

    render() {
        let {
            title
        } = this.props;

        return (
            <div className="account-sticky">
                <i className="fa fa-angle-left" onClick={this._goBack}></i>
                <h3>{title}</h3>
            </div>
        );
    }
}

export default StickyComponent;