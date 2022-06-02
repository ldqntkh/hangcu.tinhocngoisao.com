import React, {Component} from 'react';

import {
    URL_GET_LIST_PRODUCT_IDS
} from '../../../variable';
import axios from 'axios';
class SpecialProductLargeComponent extends Component {
    constructor(props) {
        super(props);
        this.state = {
            loaded : false,
            fetched : false,
            products : []
        };
    }

    componentWillMount() {
        let id = this.props.id;
        if(sessionStorage.getItem(id)) {
            let datajson = JSON.parse(sessionStorage.getItem(id));
            if (datajson.display_data && datajson.display_data.products.length > 0)
            this.setState({
                fetched : false,
                loaded : true,
                products : datajson.display_data.products
            });
        } else {
            let url = URL_GET_LIST_PRODUCT_IDS.replace('{productids}', this.props.display_data.data_type_2_special_product_id);
            axios.get (url)
                .then(resData => {
                    let _resData  = resData.data;
                    if (_resData.status !== "OK") {
                        console.log(_resData.errMsg)
                    }
                    this.setState({
                        fetched : true,
                        loaded : true,
                        products : _resData.data == null ? [] : _resData.data
                    });
                    
                    if (sessionStorage.getItem(id)) {
                        let data = JSON.parse(sessionStorage.getItem(id));
                        data.display_data = {
                            products : this.state.products
                        };
                        sessionStorage.setItem(id, JSON.stringify(data));
                    } else {
                        sessionStorage.setItem(id, JSON.stringify({
                            display_data : {
                                products : this.state.products
                            }
                        }));
                    }
                }).catch((err) => {
                    console.log(err);
                });
        }
    }

    _renderSpecialProduct = ()=> {
        let {
            loaded,
            products
        } = this.state;
        if (!loaded) {
            return <div className="loading">
                    <i className="fa fa-spinner"></i>
                </div>
        } else {
            if (products.length > 0) {
                let product = products[0];
                let rating = '';
                if (product.average_rating !== "0") {
                    rating = <div className="star-rating">
                                <span style={{width: (product.average_rating)/5 *100 + '%' }}>Rated <strong className="rating">{product.average_rating}</strong> out of 5</span>
                            </div>
                }
                let price = '';
                if (product.sale_price !== '0') {
                    // regular_price
                    price = <span className="price">
                                <ins>
                                    <span className="price-label">Khuyến mãi: </span>
                                    <span className="woocommerce-Price-amount amount">{product.sale_price}<span className="woocommerce-Price-currencySymbol">đ</span></span>
                                </ins>
                                <del>
                                    <strong class="price-label">Giá: </strong>
                                    <span className="woocommerce-Price-amount amount">{product.regular_price}<span className="woocommerce-Price-currencySymbol">đ</span></span>
                                </del>
                            </span>
                } else {
                    price = <span className="price">
                                <ins>
                                    <span className="price-label">Giá: </span>
                                    <span className="woocommerce-Price-amount amount">{product.regular_price}<span className="woocommerce-Price-currencySymbol">đ</span></span>
                                </ins>
                            </span>
                }
                return(
                    <div className="contents">
                        <div className="image">
                            <img src={product.image} alt="" />
                            <a href={product.link}>
                                <h4>Chi tiết sản phẩm</h4>
                            </a>
                        </div>
                        <div className="content">
                            <a href={product.link}>
                                <h3>{product.name}</h3>
                            </a>
                            {price}
                            {rating}
                            
                            <form className="cart" action={product.link} method="post" encType="multipart/form-data">
                                <input type="hidden" className="input-text qty text" name="quantity" value="1" pattern="[0-9]*"  />
					            <button type="submit" name="add-to-cart" value={product.id} className="single_add_to_cart_button button alt">Thêm vào giỏ hàng</button>
			                </form>
                        </div>
                    </div>
                );
            }
        }
        return <React.Fragment></React.Fragment>
    }

    render() {
        return (
            <div className="show-special-product">
                {this._renderSpecialProduct()}    
            </div>
        )
    }
}

export default SpecialProductLargeComponent;