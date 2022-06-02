import React, {Component} from 'react';


class HeaderProductTypeComponent extends Component {
    constructor(props) {
        super(props);
    }

    _SelectProductType = (product_type) => {
        this.props.SelectProductType(product_type);
    }

    render() {
        //this._clearProductTypeSelected();
        let {product_types, product_type_selected} = this.props;
        let headerItems = [];
        let flag = true;
        let classname = 'header-item';
        for(let index in product_types) {
            if (index == 0 && product_type_selected === '') {
                flag = false;
            } else {
                if (product_type_selected === product_types[index].value) {
                    flag = false;
                }
            }
            
            if (!flag) {
                classname = 'header-item active';
                flag = true;
            } else {
                classname = 'header-item';
            }
            headerItems.push(
                <div className={classname} key={index} onClick={() => this._SelectProductType(product_types[index].value)}>
                    {product_types[index].name}
                </div>
            )
        }
        
        return (
            <div className="header">
                {headerItems}
            </div>
        )
    }
}

// create container
import { connect } from 'react-redux';

import {
    SelectProductType
} from '../../../action/actionFunction';

const mapStateToProps = state => ({
    product_types : state.ProductTypeReducer.product_types,
    product_type_selected : state.ActionReducer.product_type_selected
});

const mapDispatchToProps = dispatch => ({
    SelectProductType           : product_type_selected => dispatch(SelectProductType(product_type_selected))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(HeaderProductTypeComponent);