import React, {Component} from 'react';

class ProductItemRowComponent extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        let product = this.props.product;
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
                        <del>
                            <span class="price-label">Giá: </span>
                            <span className="woocommerce-Price-amount amount">{product.regular_price}<span className="woocommerce-Price-currencySymbol">đ</span></span>
                        </del>
                        <ins>
                            <span className="price-label">Khuyến mãi: </span>
                            <span className="woocommerce-Price-amount amount">{product.sale_price}<span className="woocommerce-Price-currencySymbol">đ</span></span>
                        </ins>
                    </span>
        } else {
            price = <span className="price">
                        <ins>
                            <span className="price-label">Giá:</span>
                            <span className="woocommerce-Price-amount amount">{product.regular_price}<span className="woocommerce-Price-currencySymbol">đ</span></span>
                        </ins>
                    </span>
        }
        let stock = '';
        if (product.manage_stock && product.stock_quantity !== null) {
            // default 50 products
            let defaultProducts = 50;
            stock = <div className="stock-quantity">
                        <span> Còn lại {product.stock_quantity} sản phẩm</span>
                        <span style={{width: (product.stock_quantity)/ defaultProducts *100 + '%' }} ></span>
                    </div>
        }
        return(
            <div className="single-list">
                <ul className="post-container products">
                    <li className="product type-product status-publish has-post-thumbnail">
                        <a href={product.link} className="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                            <img src={product.image} />
                            
                            <div className="product-item-details">
                                <h2 className="woocommerce-loop-product__title">
                                    {product.name.length > 40 ? product.name.substr(0, 40) + '...' : product.name }
                                </h2>
                                { product.period != 0 && <span className="warranty_period">Bảo hành: <strong>{product.period}</strong> tháng</span> }
                                {price}
                                {rating}
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        );
    }
}

export default ProductItemRowComponent;