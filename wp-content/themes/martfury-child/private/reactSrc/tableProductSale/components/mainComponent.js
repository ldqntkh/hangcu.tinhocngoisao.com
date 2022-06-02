import React, { Component, createRef } from 'react'

import HeaderComponent from './header/headerComponent';
import BodyComponent from './body/bodyComponent';

class MainComponent extends Component {
    constructor(props) {
        super(props);
        this.bodyCpn = createRef();
    }

    setValueSearch = (data) => {
        this.bodyCpn.current.setValueSearch(data)
    }
    render() {
        return (
            <React.Fragment>
                <HeaderComponent setValueSearch={this.setValueSearch}/>
                <BodyComponent ref={this.bodyCpn}/>
            </React.Fragment>
        )
    }
}

export default MainComponent;
