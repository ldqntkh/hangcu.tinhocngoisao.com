import React, {Component} from 'react';
import Modal from 'react-modal';
import axios from 'axios';
// import component


// import container
import ChooseBodyComponent from './chooseBodyComponent';
import ChooseHeaderComponent from './chooseHeaderComponent';
// import variable
import {
    HOST_URL_API
} from '../../../../variable';

var url_api = 'get_products_by_custom_type?custom_type={0}';
class ChooseProductComponent extends Component {

    constructor(props) {
        super(props);
        Modal.setAppElement('#build-pc-function');
    }

    async componentDidUpdate(prevProps) {
        // hiện tại ko save nó vào local storage
        if (this.props.action_data.value_product_type !== prevProps.action_data.value_product_type) {
            // fetch data from server
            let value_product_type = this.props.action_data.value_product_type;
            if (value_product_type !== "") {
                if (!this.props.product_data.hasOwnProperty(value_product_type)) {
                    try {
                        // check data is exists
                        let response = await axios.get( HOST_URL_API + url_api.replace('{0}', value_product_type) );
                        let dataJson = response.data;
                        if (dataJson.success) {
                            if (dataJson.data) {
                                // filter product by require field
                                // dựa vào product type item component
                                // merge to reducer
                                this.props.InitProductDataByType({
                                    "key" : value_product_type,
                                    "value" : dataJson.data
                                });
                            }
                        } else {
                            this.props.InitProductDataByType({
                                "key" : value_product_type,
                                "value" : []
                            });
                        }
                    } catch (err) {
                        // console.log(err);
                    }
                }
            }
        }
    }
    
    closeModal = () => {
        this.props.CleanValueProductSearchKey();
        this.props.ToogleModalChooseProduct(false);
    }

    render() {
        let {action_data} = this.props;
        let value_product_type = action_data.value_product_type;
        return(
        <Modal
            isOpen={action_data.toogle_modal_choose_product}
            onAfterOpen={this.afterOpenModal}
            onRequestClose={this.closeModal}
            shouldCloseOnOverlayClick={false}
        >
            <div className="modal-header">
                <ChooseHeaderComponent />
                <i className="fa fa-close" onClick={this.closeModal}></i>
            </div>
            <div className="modal-body">
                {value_product_type !== "" && <ChooseBodyComponent product_type={value_product_type}/>}
            </div>
        </Modal>
        );
    }
}

// create container
import { connect } from 'react-redux';

import {
    ToogleModalChooseProduct,
    InitProductDataByType,
    CleanValueProductSearchKey
} from '../../../action/actionFunction';

const mapStateToProps = state => ({
    data_product_type : state.ProductTypeReducer,
    action_data : state.ActionReducer,
    product_data : state.ProductDataReducer
});

const mapDispatchToProps = dispatch => ({
    ToogleModalChooseProduct        : toogle_value => dispatch(ToogleModalChooseProduct(toogle_value)),
    CleanValueProductSearchKey      : () => dispatch(CleanValueProductSearchKey()),
    InitProductDataByType           : product_data_by_type => dispatch(InitProductDataByType(product_data_by_type))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(ChooseProductComponent);