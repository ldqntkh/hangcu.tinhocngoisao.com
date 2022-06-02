import React, { Component } from 'react'
import axios from 'axios';
import GroupProductComponent from './groupProduct/groupProductComponent';
import {URL_GET_LIST_PRODUCT_TABLE_SALE} from '../../../variable';
/**
 * Render session product name
 */

class BodyComponent extends Component {
    constructor(props) {
        super(props);
        this.list_product_sale_price = list_product_sale_price ? list_product_sale_price : [];
        this.list_product_sale_price.push({
            "cat_slug" : [],
            "name": "khac",
            "display_name" : "Khác"
        });

        this.state = {
            fetching : false,
            post_number: 50,
            start_page: 1,
            list_product_sale_price: this.list_product_sale_price,
            search: {
                ma_sp : '',
                ten_sp: ''
            }
        }
    }

    componentDidMount() {
        this._fetchData();
    }

    _arrayContainsArray = (superset, subset)=> {
        if (0 === subset.length) {
          return false;
        }
        for (let i in subset) {
            if (!String.prototype.includes) {
                // for IE
                if (superset.toString().indexOf(subset[i]) >= 0) return true;
            }
            else if (superset.includes(subset[i])) return true;
        }
        return false;
    }

    _insertItem = (lstProduct)=> {
        let that = this;
        return new Promise((resolve, reject) => {
            let list_product_sale_price = that.state.list_product_sale_price;
            for(let m = 0; m < lstProduct.length; m++) {
                let product = lstProduct[m];
                for (let i = 0; i < list_product_sale_price.length; i++) {
                    let item = list_product_sale_price[i];
                    let rs = that._arrayContainsArray(item.cat_slug, product.slugs);
                    if (rs) {
                        if (item.products) {
                            item.products.push(product);
                        } else {
                            item.products = [product];
                        }
                        break;
                    } else {
                        if (i === list_product_sale_price.length - 1) {
                            if (item.products) {
                                item.products.push(product);
                            } else {
                                item.products = [product];
                            }
                        }
                    }
                }
            }
            this.setState({
                list_product_sale_price : list_product_sale_price
            });
            resolve();
        });
    }

    setValueSearch = (data) => {
        this.setState({
            search: data
        })
    }

    _fetchData = (page = 1)=> {
        let {
            post_number, start_page
        } = this.state;
        let url = URL_GET_LIST_PRODUCT_TABLE_SALE.replace('{post_number}', post_number).replace('{start_page}', start_page);
        this.setState({
            fetching: true
        });
        axios.get(url).then(async res => {
            let resultData = res.data;
            if (resultData.status === 'OK') {
                let dataProduct = resultData.data;
                dataProduct = JSON.parse(dataProduct).data
                // for(let i in this.list_product_sale_price) {
                //     dataProduct = await this[`list_product_${i}`].current.searchProducts(dataProduct);
                // }
                await this._insertItem(dataProduct);
                page = page+1;
                this.setState({
                    start_page: page
                });
                this._fetchData(page);
            }
        }).catch(err => {
            console.log(err.message);
        }).finally(()=> {
            this.setState({
                fetching: false
            })
        });
    }

    _renderSession() {
        let result = [];
        for(let index in this.state.list_product_sale_price) {
            result.push(<GroupProductComponent search={this.state.search} item={this.state.list_product_sale_price[index]} key={index}/>)
        }
        return result;
    }

    render() {
        if (this.state.start_page === 1 && this.state.fetching) {
            return <div className="loading">
                <i className="fa fa-spinner"></i>
            </div>
        }
        return (
            this._renderSession()
        )
    }
}

export default BodyComponent;
