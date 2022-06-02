import React, { Component } from 'react';

class GroupProductComponent extends Component {

    constructor(props) {
        super(props);
    }


    _renderListProduct = (listProduct)=> {
        let search = this.props.search;

        let result = [];
        for (let i in listProduct) {
            let product = listProduct[i];
            if (search.ma_sp !==  "" || search.ten_sp !== "") {
                if (search.ma_sp !== "") {
                    if ((product.id + "").toLowerCase().indexOf(search.ma_sp.toLowerCase()) < 0) product = null;
                } else if (search.ten_sp !== "") {
                    if (product.name.toLowerCase().indexOf(search.ten_sp.toLowerCase()) < 0) product = null;
                }
            } 
            if (product == null || !product.id) continue;
            result.push(
                <div className="table-row" key={i}>
                    <div className="tb-col-1">
                        <h4>
                            <a href={product.link} target="_blank">{product.id}</a>
                        </h4>
                    </div>
                    <div className="tb-col-2">
                        <h4>
                            <a href={product.link} target="_blank">{product.name}</a>
                        </h4>
                        <div className="display-mobile">
                            <p>
                                Giá: {
                                    product.sale_price === "0" ? product.regular_price : product.sale_price
                                }đ
                            </p>
                            {
                                product.period != 0 && <p>
                                                            Bảo hành: {product.period}(tháng)
                                                        </p>
                            }
                            
                            {/* <p>
                                {
                                    product.stock_status === "instock" ? `Còn hàng ${product.stock_quantity}` : 'Hết hàng'
                                }
                            </p> */}
                        </div>
                    </div>
                    <div className="tb-col-3">
                        <h4>
                            {
                                product.sale_price === "0" ? product.regular_price : product.sale_price
                            }đ
                        </h4>
                    </div>
                    <div className="tb-col-4">
                        {
                            product.period != 0 && <h4>{product.period}(tháng)</h4>
                        }
                    </div>
                    {/* <div className="tb-col-5">
                        <span>
                            {
                                product.stock_status === "instock" ? `Còn hàng ${product.stock_quantity}` : 'Hết hàng'
                            }
                        </span>
                    </div> */}
                </div>
            )
        }
        return result;
    }

    render() {
        let item = this.props.item;
        
        let renderLstProduct = this._renderListProduct(item.products);

        if (renderLstProduct.length === 0) return null;

        return (
            <div className="group-product">
                <div className="group-name">
                    <h3>{item.display_name}</h3>
                </div>
                {
                    renderLstProduct
                }
            </div>
        )
    }
}
export default GroupProductComponent;
