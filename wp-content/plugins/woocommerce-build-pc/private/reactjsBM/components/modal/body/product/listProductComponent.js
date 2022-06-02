import React, {Component} from 'react';
import axios from 'axios';
// import variable
import {
    HOST_URL_API
} from '../../../../variable';

const url_api = 'get_products_by_custom_type?custom_type={0}';

// import component
import ProductItemComponent from './productItemComponent';

class ListProductComponent extends Component {
    constructor(props) {
        super(props);
        this.state = {
            list_product_left : [],
            list_product_right : [],
            search_product_left : '',
            search_product_right : '',
            loaded : false,
            errMsg : ''
        }
        
        if (this.props.position === 'left'){
            eventEmitter.on('addProductToState', (param => {
                this._addProductItemToState(param);
            }));
        } else {
            eventEmitter.on('updateListProductAfterFetch', (() => {
                this._updateRightListProduct();
            }));
        }
    }

    async componentWillMount() {
        await this._loadListProductData();
    }

    async componentDidUpdate(prevProps) {
        let {product_type_selected} = this.props.action_data;
        if (this.props.position === 'left') {
            if (product_type_selected !== prevProps.action_data.product_type_selected) {
                if (window.eventEmitter) {
                    window.eventEmitter.emit('showListAttribute', []);
                }
                this.setState({
                    list_product_left : [],
                    list_product_right : [],
                    search_product_left : '',
                    search_product_right : '',
                    errMsg : '',
                    loaded : false
                });
                await this._loadListProductData();
            }
        } else {
            if (product_type_selected === prevProps.action_data.product_type_selected) {
                if (JSON.stringify(this.state.list_product_right) !== JSON.stringify(this.props.list_product_reducer[product_type_selected])) {
                    this.setState({
                        list_product_right : this.props.list_product_reducer[product_type_selected],
                        errMsg : '',
                        loaded : true
                    });
                }
            }
            else if (JSON.stringify(this.props) !== JSON.stringify(prevProps)) {
                this._updateRightListProduct();
            }
        }
    }

    _updateRightListProduct = ()=> {
        let {product_type_selected} = this.props.action_data;
        let sessionProduct = sessionStorage.getItem('list_product');
        if (sessionProduct) {
            let jsonData = sessionProduct;
            jsonData = JSON.parse(jsonData)[product_type_selected];
            jsonData = this._checkListProductSelected(jsonData);
            this.props.InitProductToReducer({
                "product_type" : product_type_selected,
                "list_product" : jsonData
            });
            this.setState({
                list_product_right : jsonData,
                errMsg : '',
                search_product_right : '',
                loaded : true
            });
        }
    }

    _getProductFromServer = async ()=> {
        const {product_type_selected} = this.props.action_data;
        // check data from session storage
        // mỗi khi load left list product sẽ emit 1 sự kiện để load list attribute
        let list_product = [];
        let sessionProduct = sessionStorage.getItem('list_product');
        if (sessionProduct) {
            let jsonData = sessionProduct;
            jsonData = JSON.parse(jsonData)[product_type_selected];
            
            if(jsonData) {
                list_product = jsonData;
            }
        }
        if (list_product.length > 0) {
            this._checkListProductSelected(list_product);
            if (window.eventEmitter) {
                window.eventEmitter.emit('showListAttribute', list_product);
            }
            this.setState({
                list_product_left : list_product,
                search_product_left : '',
                errMsg : '',
                loaded : true
            })
        } else {
            try {
                // check data is exists
                let response = await axios.get( HOST_URL_API + url_api.replace('{0}', product_type_selected) );

                let dataJson = response.data;
                if (dataJson.success) {
                    if (dataJson.data.length > 0) {
                        if (dataJson.success) {
                            let data = {};
                            if (sessionProduct) {
                                data = JSON.parse(sessionProduct);
                            } 
                            data[product_type_selected] = dataJson.data;
                            sessionStorage.setItem('list_product', JSON.stringify(data));
                            this._checkListProductSelected(dataJson.data);
                            if (window.eventEmitter) {
                                window.eventEmitter.emit('showListAttribute', dataJson.data);
                            }
                            this.setState({
                                list_product_left : dataJson.data,
                                search_product_left : '',
                                errMsg : '',
                                loaded : true
                            });
                            if (window.eventEmitter) {
                                window.eventEmitter.emit('updateListProductAfterFetch');
                            }
                        } else {
                            if (window.eventEmitter) {
                                window.eventEmitter.emit('showListAttribute', []);
                            }
                            this.setState({
                                list_product_left : [],
                                search_product_left : '',
                                errMsg : dataJson.errMsg,
                                loaded : true
                            });
                        }
                    } else {
                        if (window.eventEmitter) {
                            window.eventEmitter.emit('showListAttribute', []);
                        }
                        this.setState({
                            list_product_left : [],
                            list_product_right : [],
                            search_product_left : '',
                            search_product_right : '',
                            errMsg : 'Không có sản phẩm nào thuộc loại này!',
                            loaded : true
                        });
                    }
                }
            } catch (err) {
                if (window.eventEmitter) {
                    window.eventEmitter.emit('showListAttribute', []);
                }
                this.setState({
                    list_product_left : [],
                    list_product_right : [],
                    search_product_left : '',
                    search_product_right : '',
                    errMsg : err.message,
                    loaded : true
                });
            }
        }
    }

    _checkListProductSelected = (list_product)=> {
        // check product is selected
        let sessionProductSelected = sessionStorage.getItem('list-product-selected');
        let lst_product_selected = [];
        if (sessionProductSelected) {
            const {product_type_selected} = this.props.action_data;
            let arrObj = JSON.parse(sessionProductSelected)[product_type_selected];
            if (arrObj && arrObj.length > 0) {
                for (let index in list_product) {
                    for (let i in arrObj) {
                        if (list_product[index] && list_product[index].id === arrObj[i].id) {
                            lst_product_selected.push(list_product[index]);
                            list_product.splice(index , 1);
                            //break;
                        }
                    }
                }
            }
        }
        return lst_product_selected;
    }

    _loadListProductData = async ()=> {
        /**
         * tùy thuộc vào props position mà sẽ quyết định sẽ load data từ server(hoặc từ session storage nếu có)
         * ngược lại (right) sẽ lấy từ reducer
         * **** xử lý trường hợp edit product
         */
        const {position, action_data} = this.props;
        const {product_type_selected} = action_data;
        if (position === 'left') {
            await this._getProductFromServer();
        } else {
            let sessionProduct = sessionStorage.getItem('list_product');
            if (sessionProduct) {
                let jsonData = sessionProduct;
                jsonData = JSON.parse(jsonData)[product_type_selected];
                jsonData = this._checkListProductSelected(jsonData);
                this.props.InitProductToReducer({
                    "product_type" : product_type_selected,
                    "list_product" : jsonData
                });
                this.setState({
                    list_product_right : jsonData,
                    search_product_right : '',
                    errMsg : '',
                    loaded : true
                });
            }
        }
    }

    /**
     * Tại sao không dùng reducer cho 2 func này??!
     */
    _removeProductItemFromState = (productId)=> {
        let list_product = [...this.state.list_product_left];
        for (let index in list_product) {
            if (list_product[index].id === productId) {
                list_product.splice(index, 1);
                break;
            }
        }
        if (window.eventEmitter) {
            window.eventEmitter.emit('showListAttribute', list_product);
        }
        this.setState({
            list_product_left : list_product,
            search_product_left : ''
        });
        //this._setNewListProductToStorage(list_product);
    }

    _addProductItemToState = (product)=> {
        let list_product = [...this.state.list_product_left];
        list_product.splice(product.index ? product.index : 0, 0, product);
        list_product.join();
        if (window.eventEmitter) {
            window.eventEmitter.emit('showListAttribute', list_product);
        }
        this.setState({
            list_product_left : list_product,
            search_product_left : ''
        });
        //this._setNewListProductToStorage(list_product);
    }

    _setNewListProductToStorage = (list_product) => {
        // set list product to session storage to user after
        let sessionProduct = sessionStorage.getItem('list_product');
        if (sessionProduct) {
            const {product_type_selected} = this.props.action_data;
            sessionProduct = JSON.parse(sessionProduct);
            sessionProduct[product_type_selected] = list_product;
            sessionStorage.setItem('list_product', JSON.stringify(sessionProduct));
        }
    }

    _SearchProduct = (e)=> {
        const {position} = this.props;
        if (position === 'left') {
            this.setState({
                search_product_left : e.target.value
            });
        } else {
            this.setState({
                search_product_right : e.target.value
            });
        }
    }

    _findProductByFilter = ()=> {
        const {position} = this.props;
        const {
            list_product_left, 
            list_product_right
        } = this.state;
        // for now, I will not filter product right session
        if (position === 'right') return list_product_right;
        let list_product = position === 'left' ?  list_product_left :  list_product_right;
        list_product = typeof list_product === 'undefined' ? [] : list_product;
        let {product_search_attribute} = this.props.action_data;
        let result = [];
        // không dùng regex, for toàn bộ attr
        let keys = Object.keys(product_search_attribute);
        for (let i in keys) {
            let key = keys[i];
            if (product_search_attribute[key].length > 0) {
                for(let index in list_product) {
                    let attributes = list_product[index].attributes;
                    let item = attributes.find(o => o.name === key);
                    if(item) {
                        item = item.values;
                        for (let m in item) {
                            if (product_search_attribute[key].includes(item[m].slug)) {
                                if (result.includes(list_product[index])) {
                                    break;
                                } else {
                                    result.push(list_product[index]);
                                    break;
                                }
                            }
                        }
                    }
                } 
            }
        }
        if (result.length === 0) return list_product;
        else return result;
    }

    render() {
        const {position} = this.props;
        const {
                search_product_left, 
                search_product_right, 
                errMsg,
                loaded
            } = this.state;
        const parentClass = position === 'left' ? 'left-list-product' : 'right-list-product';
        if (!loaded) {
            return (
                <span className="loading">Đang tải dữ liệu từ server!</span>
            )
        } else {
            if (errMsg !== '') {
                return <span className="error">{errMsg}</span>
            }
            
            let searchKey = position === 'left' ?  search_product_left :  search_product_right;
            let list_product = this._findProductByFilter();
            let lst_product_render = list_product.map((item, index) => {
                if ( item.name.toLowerCase().includes(searchKey.toLowerCase()) ) {
                    return <ProductItemComponent key={index} 
                            product={item} 
                            index={index}
                            position={position} 
                            removeProduct={this._removeProductItemFromState}/>
                }
            })
            return(
                <div className={parentClass}>
                    <input type="text" className="search-product" 
                                placeholder="Tìm kiếm sản phẩm theo tên" 
                                defaultValue={position === 'left' ? search_product_left : search_product_right}
                                onChange={this._SearchProduct} />
                    <div className="">
                        {lst_product_render}
                    </div>
                </div>
            )
        }
    }
}


// create container
import { connect } from 'react-redux';

import {
    InitProductToReducer
} from '../../../../action/actionFunction';

const mapStateToProps = state => ({
    //action_value : state.ActionReducer,
    action_data : state.ActionReducer,
    list_product_reducer : state.ListProductReducer
});

const mapDispatchToProps = dispatch => ({
    InitProductToReducer        : data => dispatch(InitProductToReducer(data))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(ListProductComponent);