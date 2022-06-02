import React, {Component} from 'react';

class AttributeItemDetailComponent extends Component {

    constructor(props) {
        super(props);
    }

    _handleChange = (e)=> {
        let item = this.props.item;
        let product_search_attribute = this.props.action_data.product_search_attribute;
        let flag = false;
        let index = 0;
        // group search attribute
        if (!product_search_attribute.hasOwnProperty(item.group)) {
            product_search_attribute[item.group] = [item.slug];
        } else {
            for(index = 0; index < product_search_attribute[item.group].length; index ++) {
                if (product_search_attribute[item.group][index] === item.slug) {
                    flag = true;
                    break;
                }
            }
            if (flag) {
                product_search_attribute[item.group].splice(index, 1);
            } else {
                product_search_attribute[item.group].push(item.slug);
            }
        }
        if (product_search_attribute[item.group].length == 0) delete product_search_attribute[item.group];
        
        this.props.SetValueProductSearchAttribute(product_search_attribute);
    }

    render() {
        let item = this.props.item;
        return(
            <div className="input-group">
                <input type="checkbox" id={item.group + '_' + item.slug} name={item.group + '_' + item.slug} value={item.name} 
                    onChange={this._handleChange} />
                <label htmlFor={item.group + '_' + item.slug}> {item.name} ({item.count}) </label>
            </div>
        );
    }
}

// create container
import { connect } from 'react-redux';

import {
    SetValueProductSearchAttribute,
} from '../../../../../action/actionFunction';

const mapStateToProps = state => ({
    action_data : state.ActionReducer
});

const mapDispatchToProps = dispatch => ({
    SetValueProductSearchAttribute : product_search_attribute => dispatch(SetValueProductSearchAttribute(product_search_attribute))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(AttributeItemDetailComponent);