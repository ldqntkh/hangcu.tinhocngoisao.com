import React, {Component} from 'react';
// import component
import ProductDetailsComponent from './productDetailsComponent';

class ProductTypeItemComponent extends Component {

    constructor(props) {
        super(props);
    }

    checkProductRequire = ()=> {
        let {product_type, computer_building_data} = this.props;
        if (product_type['require-by'] !== null) {
            for (let index in  product_type['require-by']) {
                if (computer_building_data[product_type['require-by'][index]].product === null) {
                    alert(`Vui lòng chọn một sản phẩm từ ${product_type['require-by'][index]} để tiếp tục!`);
                    return false;
                }
            }
            return true;
        } else {
            return true;
        }
    }

    showModalChooseProduct = ()=> {
        // check product require
        if (this.checkProductRequire()) {
            this.props.CleanValueProductSearchKey();
            // setup data modal
            this.props.SetValueProductType(this.props.product_type.value);
            // show modal
            this.props.ToogleModalChooseProduct(true);
        }
    }

    checkProductTypeExists = (product_type)=> {
        let {computer_building_data} = this.props;
        
        if (computer_building_data[product_type] && computer_building_data[product_type]['product'] !== null) 
            return computer_building_data[product_type];
        
        return false;
    }

    render() {
        let {product_type} = this.props;
        let product_data = this.checkProductTypeExists(product_type.value);
        return(
            <React.Fragment>
            <div className="product-type-item">
                <div className="left-content">
                    {this.props.index} . {product_type.name} 
                    {product_type.require ? <span className="require">&nbsp;*</span> : null}
                </div>
                <div className="right-content">
                    {
                        /**
                         * check nếu đã chọn 1 product nào đó thì render product đó
                         * nếu chưa thì render button chon product
                         */
                    }
                    {
                        !product_data ? 
                            <button className="choose-product" onClick={this.showModalChooseProduct}>
                                <i className="fa fa-plus"></i>
                                {product_type.name}
                            </button>
                        :
                            <ProductDetailsComponent product_type={product_type.value} product_data = {product_data} />
                    }
                </div>
            </div>
            </React.Fragment>
        );
    }
}

// create container
import { connect } from 'react-redux';

import {
    ToogleModalChooseProduct,
    CleanValueProductSearchKey,
    SetValueProductType
} from '../../../action/actionFunction';

const mapStateToProps = state => ({
    computer_building_data : state.ComputerBuildingDataReducer
});

const mapDispatchToProps = dispatch => ({
    ToogleModalChooseProduct        : toogle_value => dispatch(ToogleModalChooseProduct(toogle_value)),
    CleanValueProductSearchKey      : () => dispatch(CleanValueProductSearchKey()),
    SetValueProductType             : product_type => dispatch(SetValueProductType(product_type)),
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(ProductTypeItemComponent);