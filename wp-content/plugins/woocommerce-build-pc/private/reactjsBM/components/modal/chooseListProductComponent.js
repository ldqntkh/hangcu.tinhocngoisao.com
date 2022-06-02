import React, {Component} from 'react';
import Modal from 'react-modal';

// import component
import HeaderProductTypeComponent from './header/headerProductTypeComponent';
import LeftListProductComponent from './body/leftListProductComponent';
import RightListProductComponent from './body/rightListProductComponent';

class ChooseListProductComponent extends Component {

    constructor(props) {
        super(props);
        Modal.setAppElement('#select_product');
    }

    componentWillMount() {
    }
    
    closeModal = () => {
        //this.props.CleanValueProductSearchKey();
        // list-product-selected
        // #selected_product_value
        let listProductSelected = sessionStorage.getItem('list-product-selected');
        document.getElementById('selected_product_value').value = btoa(listProductSelected);

        this.props.ToogleModalChooseProduct(false);
        this.props.InitMessageCloseModal('Toàn bộ dữ liệu về BuildPC đã được lưu lại. Vui lòng cập nhật sản phẩm!');
    }

    render() {
        let {action_value} = this.props;
        return(
        <Modal
            isOpen={action_value.toogle_modal_choose_product}
            onAfterOpen={this.afterOpenModal}
            onRequestClose={this.closeModal}
            shouldCloseOnOverlayClick={false}
        >
            <div className="modal-header">
                <i className="fa fa-close" onClick={this.closeModal}></i>
                {
                    /**
                     * render header product type
                     */
                }
                <HeaderProductTypeComponent />
            </div>
            <div className="modal-body">
            {
                /**
                 * render body product from server api
                 * phân tách 2 phần 1 bên load từ server, bên còn lại load từ list product được chọn
                 * dùng chung 1 component cho cả 2
                 * vấn đề làm sao render ra danh sách đã chọn để save lên server
                 * lúc edit sản phẩm
                 * trong mỗi bên có
                 *  1 thanh search product
                 *  1 list product
                 *  ko cần paging
                 */
            }
                <div className="header-content">
                    <h1>Danh sách sản phẩm đang có</h1>
                    <h1>Danh sách sản phẩm đã chọn</h1>
                </div>
                <div className="modal-body-content">
                    <LeftListProductComponent />
                    <RightListProductComponent />
                </div>
            </div>
        </Modal>
        );
    }
}

// create container
import { connect } from 'react-redux';

import {
    InitMessageCloseModal,
    ToogleModalChooseProduct
} from '../../action/actionFunction';

const mapStateToProps = state => ({
    action_value : state.ActionReducer
});

const mapDispatchToProps = dispatch => ({
    ToogleModalChooseProduct        : modal_toogle_value => dispatch(ToogleModalChooseProduct(modal_toogle_value)),
    InitMessageCloseModal           : message => dispatch(InitMessageCloseModal(message))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(ChooseListProductComponent);