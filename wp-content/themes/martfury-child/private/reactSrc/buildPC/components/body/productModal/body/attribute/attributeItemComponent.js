import React, {Component} from 'react';

// import component
import AttributeItemDetailComponent from './attributeItemDetailComponent';

class AttributeItemComponent extends Component {

    constructor(props) {
        super(props);
        this.state = {
            show_filter : false
        }
    }

    toogleFilter = ()=> {
        let show_filter = this.state.show_filter;
        this.setState({
            show_filter : !show_filter
        })
    }

    render() {
        let {attribute_value} = this.props;
        if ( attribute_value.length == 0 ) return null;
        return(
            <div className={ `attribute-item ${this.state.show_filter ? 'show' : ''}` }>
                <div className="header-attr-name" onClick={this.toogleFilter}>
                    <h4>{attribute_value[0].full_name}</h4>
                    <i className="fa fa-angle-down angle-down"></i>
                </div>
                <div className="body-attr-value">
                    {
                        attribute_value.map((item, index) => <AttributeItemDetailComponent item={item} key={index}/>)
                    }
                </div>
            </div>
        );
    }
}

// create container
import { connect } from 'react-redux';

import {
    
} from '../../../../../action/actionFunction';

const mapStateToProps = state => ({
    
});

const mapDispatchToProps = dispatch => ({
    
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(AttributeItemComponent);