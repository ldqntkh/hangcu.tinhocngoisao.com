import React from 'react';
import axios from 'axios';
import FormData from 'form-data';
import {
    Link
} from "react-router-dom";
import { hangcu_home_ajax } from '../../variable/variables';
import { formatPrice } from '../../variable/functions';


class ListOrderComponent extends React.Component {

    constructor( props ) {
        super( props );
        this.state = {
            page: 1,
            total_order: 0,
            orders: []
        }
    }

    async componentDidMount() {
        let page = this.props.match.params.page;
        if( !page || isNaN(page) ) page = 1;
        if( page && !isNaN(page) ) {
            this.setState({
                page: parseInt( page )
            }, async()=> await this._getListOrders())
        }
    }

    async componentDidUpdate(prevProps, prevState) {
        let page = this.props.match.params.page;
        if( !page || isNaN(page) ) page = 1;
        if( this.state.page != page ) {
            if( page && !isNaN(page) ) {
                window.scrollTo(0, 0)
                this.setState({
                    page: parseInt( page )
                }, async ()=> await this._getListOrders())
            }
        }
    }

    _getListOrders = async()=> {
        try {
            let page = this.state.page;

            document.body.classList.add('hangcu_loading');

            var data = new FormData();
            data.append('action', 'hc_get_list_orders');
            data.append('page', page);

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );

            if( response.data.success ) {
                let orders = response.data.data.orders;
                if( !orders || !orders.length ) {
                    // continue
                } else {
                    this.setState({ orders, total_order: response.data.data.total_order })
                }
            } else {

            }

        } catch (err) {
            console.log(err);
        } finally {
            document.body.classList.remove('hangcu_loading');
        }
    }

    _buyOrder = async(order_id) =>{
        let hasTrue = false;
        try {
            document.body.classList.add('hangcu_loading');

            var data = new FormData();
            data.append('action', 'hc_re_buy_order');
            data.append('order_id', order_id);

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );

            if( response.data.success ) {
                // console.log(response.data)
                location.href = response.data.data.redirect;
            } else {
                alert( response.data.data.msg );
                this.props.history.push('/tai-khoan/orders');
            }

        } catch (err) {
            console.log(err);
        } finally {
            hasTrue && document.body.classList.remove('hangcu_loading');
        }
    }

    _renderOrderItems = ()=> {
        let {
            orders
        } = this.state;

        let results = [];

        for( let i =0; i < orders.length; i++ ) {
            let order = orders[i];
            let link_view_order = '/tai-khoan/' + order.view_link.split('/tai-khoan/')[1];
            results.push(
                <>
                    <div className="order-line-item" key={order.ID}>
                        <div className="head">
                            <span>Mã đơn hàng: {order.ID} <span className="hide-mobile">| Ngày mua: {order.created_at}</span></span>
                            <span>{order.status}</span>
                        </div>
                        <div className="content">
                            <div className="product">
                                <img src={order.products[0].image} alt={order.products[0].name} />
                                <div className="pd-name">
                                    <p>{order.products[0].name}</p>
                                    <p>Số lượng: {order.products[0].quantity}</p>
                                </div>
                            </div>
                            <div className="pd-price">
                                {
                                    order.products[0].sub_total < order.products[0].total ?
                                    <>
                                        <span className="has-sub-total" dangerouslySetInnerHTML={{ __html:formatPrice(order.products[0].total) }}></span>
                                        <span className="total" dangerouslySetInnerHTML={{ __html:formatPrice(order.products[0].sub_total) }}></span>
                                    </>
                                    :
                                    <>
                                        <span className="total" dangerouslySetInnerHTML={{ __html:formatPrice(order.products[0].total) }}></span>
                                    </>
                                }
                            </div>
                        </div>
                        <div className="footer">
                            <div className="left">
                                <p>{order.total_product} sản phẩm</p>
                            </div>
                            <div className="right">
                                <p>Tổng đơn hàng: <strong dangerouslySetInnerHTML={{ __html:formatPrice(order.sub_total) }}></strong></p>
                                <div className="btns">
                                    <Link to={link_view_order}>Xem chi tiết</Link>
                                    <a  onClick={()=>this._buyOrder(order.ID)}>Mua lại</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </>
            )
        }
        return results;
    }

    _renderPaging = ()=> {
        let {
            total_order, page
        } = this.state;
        let rs = [];
        if( !total_order ) return null;
        if( total_order <= 10 ) {
            rs.push( <p key={`un-page`} className="paging-item active">
                <Link to={'#'}>{1}</Link>
            </p> );
        } else {

            let total_page = parseInt((total_order / 10)) + ( total_order % 10 != 0 ? 1 : 0 );
            if( total_page < 7 ) {
                for( let i = 1; i <= total_page; i++ ) {
                    rs.push( <p className={ i == page ? "paging-item active" : 'paging-item' } key={`page-${i}`}>
                            <Link to={ i == page ? '#' : `/tai-khoan/orders/${i}` }>{i}</Link>
                        </p> )
                }

            } else {
                let prevPage = page;
                let preTotal = 0;
                let nextPage = page;
                let nextTotal = 0;

                if( page > 1 ) {
                    while( preTotal < 3 && prevPage > 1 ) {
                        prevPage --;
                        preTotal++;
                    }
                }

                if( page < total_page ) {
                    while( nextTotal < 3 && nextPage < total_page ) {
                        nextPage ++;
                        nextTotal++;
                    }
                }

                for( let i = prevPage; i <= nextPage; i++ ) {
                    rs.push( <p className={ i == page ? "paging-item active" : 'paging-item' } key={`page-${i}`}>
                            <Link to={ i == page ? '#' : `/tai-khoan/orders/${i}` }>{i}</Link>
                        </p> )
                }
            }

            if( page > 1 ) {
                rs.unshift(
                    <p className={ 'paging-item-next' } key={`prev-page-${page}`}>
                        <Link to={ `/tai-khoan/orders/${page-1}` }><i className="fa fa-angle-left"></i></Link>
                    </p>
                );
            }
            if( page < total_page ) {
                rs.push(
                    <p className={ 'paging-item-next' } key={`next-page-${page}`}>
                        <Link to={ `/tai-khoan/orders/${page+1}` }><i className="fa fa-angle-right"></i></Link>
                    </p>
                );
            }

        }
        if( !rs.length ) return null;
        return(
            <div className="paging-order">
                {
                    rs
                }
            </div>
        )
    }

    _renderEmptyOrder = ()=> {
        return (
            <div className="empty-order">
                <p>Đơn hàng trống, mua sắm ngay thôi</p>
                <a href={shopUrl}>Đến cửa hàng</a>
            </div>
        )
    }

    render() {
        let {
            orders
        } = this.state;

        return(
            <div className="order-container">
                <h3>Quản lý đơn hàng</h3>
                <div className="list-order-container">
                    {
                        orders.length > 0 ?
                        this._renderOrderItems()
                        :
                        this._renderEmptyOrder()
                    }
                </div>
                {
                    orders.length > 0 && this._renderPaging()
                }

            </div>
        );
    }
}

export default ListOrderComponent;