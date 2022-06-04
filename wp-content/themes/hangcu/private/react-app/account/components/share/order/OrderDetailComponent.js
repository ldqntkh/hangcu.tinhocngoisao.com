import React from 'react';
import axios from 'axios';
import FormData from 'form-data';
import { hangcu_home_ajax } from '../../variable/variables';
import { formatPrice } from '../../variable/functions';

class OrderDetailComponent extends React.Component {
    constructor( props ) {
        super(props)
        this.state = {
            order_id: '',
            order: null,
            showPopupCancel: false,
            cancelOrderValue: '',
            cancelOrderNote: '',
            errMsg : ''
        }
    }

    async componentDidMount() {
        let order_id = this.props.match.params.order_id;
        
        if( order_id ) {
            this.setState({
                order_id: order_id
            });
            await this._getOrderDetail( order_id );
        } else {
            // back to orders page
        }
    }

    _buyOrder = async() =>{
        let order_id = this.state.order_id;
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

    _getOrderDetail = async(order_id)=> {
        let hasTrue = true;
        try {
            document.body.classList.add('hangcu_loading');

            var data = new FormData();
            data.append('action', 'hc_get_order_detail');
            data.append('order_id', order_id);

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            
            if( response.data.success ) {
                this.setState({
                    order: response.data.data
                })
            } else {
                alert( response.data.data.msg );
                this.props.history.push('/tai-khoan/orders');
                hasTrue = false;
            }

        } catch (err) {
            console.log(err);
        } finally {
            hasTrue && document.body.classList.remove('hangcu_loading');
        }
    }

    _showPopupConfirmCancelOrder = ()=> this.setState({ showPopupCancel: true })
    _hidePopupConfirmCancelOrder = ()=> this.setState({ showPopupCancel: false })

    _selectCancelOrder = (e)=> {
        this.setState({
            cancelOrderValue: e.target.value
        })
    }
    _cancelOrderNote = (e)=> {
        this.setState({
            cancelOrderNote: e.target.value
        })
    }

    _cancelOrder = async()=> {
        let {
            cancelOrderValue, cancelOrderNote, order_id
        } = this.state;
        this.setState({
            errMsg: ''
        });
        if( cancelOrderValue.trim() == '' ) {
            this.setState({
                errMsg: 'Vui lòng chọn lý do hủy đơn'
            });
            return false
        }  
        if ( cancelOrderNote.trim() == '' ) {
            this.setState({
                errMsg: 'Vui lòng cho biết lý do hủy đơn'
            });
            return false
        }

        let hasTrue = true;
        try {
            document.body.classList.add('hangcu_loading');

            var data = new FormData();
            
            data.append('action', 'hc_pending_cancel_order');
            data.append('order_id', order_id);
            data.append('order_cancel_value', cancelOrderValue);
            data.append('order_cancel_note', cancelOrderNote);

            let response = await axios.post(
                hangcu_home_ajax,
                data
            );
            
            if( response.data.success ) {
                await this._getOrderDetail( order_id );
                this.setState({
                    showPopupCancel: false,
                    cancelOrderNote: '',
                    cancelOrderValue: ''
                })
                hasTrue = true;
            } else {
                alert( response.data.data.msg );
                this.props.history.push('/tai-khoan/orders');
                hasTrue = false;
            }

        } catch (err) {
            console.log(err);
        } finally {
            hasTrue && document.body.classList.remove('hangcu_loading');
        }
    }

    render() {
        let {
            order, order_id, showPopupCancel, cancelOrderNote, errMsg
        } = this.state;
        if( !order ) return null;
        return(
            <div className="order-container">
                <div className="header-detail">
                    <h3>Chi tiết đơn hàng #{order_id} &nbsp; <strong>{order.status}</strong></h3>
                    <p>Ngày đặt hàng: {order.created_at}</p>
                </div>
                <div className="order-detail-container">
                    {/* địa chỉ nhận hàng */}
                    <div className="head">
                        <div className="block block-address">
                            <h4>Địa chỉ người nhận</h4>
                            <div className="block-content">
                                <p>{`${order.order_address.first_name} ${order.order_address.last_name}`}</p>
                                <p>Địa chỉ: {`${order.order_address.address_1}, ${order.order_address.address_2}, ${order.order_address.city}, ${order.order_address.state}`}</p>
                                <p>Điện thoại: {order.order_address.phone}</p>
                            </div>
                        </div>
                        <div className="block block-payment">
                            <h4>Hình thức thanh toán</h4>
                            <div className="block-content">
                                <p>{order.payment_method_title}</p>
                            </div>
                        </div>
                        {
                            order.vat && order.vat.company &&
                            <div className="block block-vat" style={{ width: "100%", marginTop: 10 }}>
                                <h4>Thông tin xuất hóa đơn VAT</h4>
                                <div className="block-content">
                                    <p style={{ textTransform: 'none' }}>Tên công ty: {order.vat.company}</p>
                                    <p>Mã số thuế: {order.vat.tax_code}</p>
                                    <p>Địa chỉ: {order.vat.address}</p>
                                    <p>Email: {order.vat.email}</p>
                                </div>
                            </div>
                        }
                        
                    </div>
                
                    <div className="order-detail">
                        <div className="products">
                            {
                                order.products.map( (product, index)=> {
                                    return ( 
                                        <div className="product-line-item" key={index}>
                                            <div className="product">
                                                <img src={product.image} alt={product.name} />
                                                <div className="pd-name">
                                                    <p>{product.name}</p>
                                                    <p>Số lượng: {product.quantity}</p>
                                                </div>
                                            </div>
                                            <div className="pd-price">
                                                {
                                                    product.sub_total < product.total ?
                                                    <>
                                                        <span className="has-sub-total" dangerouslySetInnerHTML={{ __html:formatPrice(product.total) }}></span>
                                                        <span className="total" dangerouslySetInnerHTML={{ __html:formatPrice(product.sub_total) }}></span>
                                                    </>
                                                    :
                                                    <>
                                                        <span className="total" dangerouslySetInnerHTML={{ __html:formatPrice(product.total) }}></span>
                                                    </>
                                                }
                                            </div>
                                        </div>
                                    )
                                } )
                            }
                        </div>
                        <div className="order-total">
                            <div>
                                <p className="t-left">Tạm tính:</p>
                                <p className="t-right" dangerouslySetInnerHTML={{__html: formatPrice(order.total)}}></p>
                            </div>
                            {/* {
                                order.shipping_fee &&
                                <>
                                    <div>
                                        <p className="t-left">Phí vận chuyển:</p>
                                        <p className="t-right" dangerouslySetInnerHTML={{__html: formatPrice(order.shipping_fee)}}></p>
                                    </div>
                                </>
                            } */}
                            <div>
                                <p className="t-left">Thành tiền:</p>
                                <p style={{ color: "#ED1C24", fontWeight: 'bold' }} className="t-right" dangerouslySetInnerHTML={{__html: formatPrice(order.sub_total)}}></p>
                            </div>
                            <div>
                                <p className="btns">
                                    {/* {
                                        order.status_code == 'processing' && order.payment_method == 'cod'
                                        &&
                                        <a className='cancel-order' onClick={this._showPopupConfirmCancelOrder}>Hủy đơn hàng</a>
                                    } */}
                                    <a onClick={this._buyOrder}>Mua lại</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {
                    showPopupCancel &&
                    <div id="popup-account">
                        <div className="form-cancel-order">
                            <h3>Hủy đơn hàng #{order.ID}</h3>
                            <span id="close-login-popup" className="electro-close-icon" onClick={this._hidePopupConfirmCancelOrder}></span>
                            <div className="cancel-order-contents">
                                <div className="select-cancel-values">
                                    <label htmlFor="select-cancel-value">Chọn lý do hủy đơn</label><br/>
                                    <select id="select-cancel-value" required onChange={this._selectCancelOrder}>
                                        <option value="">Lý do hủy đơn</option>
                                        {
                                            order.cancel_order_values.map( (_cancel, index) => {
                                                return <option key={index} value={_cancel}>{_cancel}</option>
                                            } )
                                        }
                                    </select>
                                </div>
                                <div className="input-cancel-notes">
                                    <label htmlFor="input-cancel-note">Nội dung hủy đơn hàng</label>
                                    <textarea id="input-cancel-note" required rows="4" cols="50" value={cancelOrderNote} placeholder="Nội dung hủy đơn hàng..." onChange={this._cancelOrderNote}></textarea>
                                </div>
                                {
                                    errMsg && <p className="error">{errMsg}</p>
                                }
                                <div className="btns">
                                    <button type="button" className="btn btn-primary btn-delete-address" onClick={this._cancelOrder}>
                                        <i className="far fa-trash-alt"></i>
                                        <span>Xác nhận</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                }
            </div>
        )
    }
}

export default OrderDetailComponent;