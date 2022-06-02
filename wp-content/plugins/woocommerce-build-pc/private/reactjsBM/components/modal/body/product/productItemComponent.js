import React, {Component} from 'react';

class ProductItemComponent extends Component {
    constructor (props) {
        super(props);
    }

    _AddProductToList = (product)=> {
        product.index = this.props.index;
        this.props.AddProductToList({
            product_type : this.props.product_type_selected,
            product
        });

        // remove product from left list product
        this.props.removeProduct(product.id)
    }

    _RemoveProductFromList = (product)=> {
        this.props.RemoveProductFromList({
            product_type : this.props.product_type_selected,
            product
        });
        
        if (window.eventEmitter) {
            window.eventEmitter.emit('addProductToState', product);
        }
    }

    render() {
        const {product, position} = this.props; 
        let buttonName = position === 'left' ? 'Chọn' : 'Xóa';
        let buttonFunc = position === 'left' ? ()=>this._AddProductToList(product) : ()=>this._RemoveProductFromList(product);
        return (
            <div className="product-item">
                <a href={product.link} target="_blank">
                    <img src={product.image} alt="" />
                    <div className="content">
                        <span className="product-name">{product.name}</span>
                        <span className="product-id">{product.id}</span>
                    </div>
                </a>
                <button type="button" onClick={buttonFunc}>{buttonName}</button>
            </div>
        );
    }
}

// create container
import { connect } from 'react-redux';

import {
    RemoveProductFromList,
    AddProductToList
} from '../../../../action/actionFunction';

const mapStateToProps = state => ({
    //action_value : state.ActionReducer,
    product_type_selected : state.ActionReducer.product_type_selected
});

const mapDispatchToProps = dispatch => ({
    RemoveProductFromList        : data => dispatch(RemoveProductFromList(data)),
    AddProductToList             : data => dispatch(AddProductToList(data)),
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(ProductItemComponent);