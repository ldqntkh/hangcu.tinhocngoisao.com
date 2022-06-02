import React, {Component} from 'react';

class ProductDetailsComponent extends Component {

    constructor (props){
        super(props);
        this.state = {
            quantity: this.props.product_data.quantity
        }
    }

    ClearValueComputerProductByType = ()=> {
        // check link product
        let {
            data_product_type,
            product_type
        } = this.props;
        for(let index in data_product_type) {
            if (data_product_type[index]['require-by'] && data_product_type[index]['require-by'].indexOf(product_type) >= 0) {
                this.props.ClearValueComputerProductByType({
                    "type" : data_product_type[index]['value']
                });
            }
        }
        this.props.ClearValueComputerProductByType({
            "type" : product_type
        });
    }

    _handleChangeQuantity = (e)=> {
        this.props.SetQuantityOfComputerProduct({
            "type": this.props.product_type,
            "value" : parseInt(e.target.value)
        })
        this.setState({
            quantity : e.target.value
        });
    }

    render () {
        let product_data = this.props.product_data;
        let product_type = this.props.product_type;
        let product = product_data.product;
        let rating = null;
        let price = (product.sale_price !== "0" && product.sale_price !== "") ? product.sale_price : product.regular_price;
        if (product.average_rating !== "0") {
            rating = <div className="star-rating">
                        <span style={{width: (product.average_rating)/5 *100 + '%' }}>Rated <strong className="rating">{product.average_rating}</strong> out of 5</span>
                    </div>
        }
        return(
            <div className="choose-product-item-detail">
                <div className="image">
                    <img src={product.image}/>
                </div>
                <div className="content">
                    <a href={product.link} target="_blank">
                        <p className="name"> {product.name} </p>
                        <p className="price"> Giá: {price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")} đ</p>
                        <p className="productid"> Mã sản phẩm: {product.id} </p>
                        { rating }
                    </a>
                    <div className="action">
                        <p className="input-group">
                            <span>Số lượng : </span>
                            <input name={`${product_type}_quantity`} type="number" min="1" max={product.stock_quantity} value={this.state.quantity} onChange={this._handleChangeQuantity}/>
                            <span> = <strong className="price">{ (parseFloat(price) * this.state.quantity).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") }đ</strong> </span>
                        </p>
                        <div className="buttons">
                            <button className="fa fa-trash" onClick={this.ClearValueComputerProductByType}></button>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}
// create container
import { connect } from 'react-redux';

import {
    SetQuantityOfComputerProduct,
    ClearValueComputerProductByType
} from '../../../action/actionFunction';

const mapStateToProps = state => ({
    data_product_type : state.ProductTypeReducer
});

const mapDispatchToProps = dispatch => ({
    SetQuantityOfComputerProduct        : computer_product_data => dispatch(SetQuantityOfComputerProduct(computer_product_data)),
    ClearValueComputerProductByType     : computer_product_data => dispatch(ClearValueComputerProductByType(computer_product_data)),
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(ProductDetailsComponent);