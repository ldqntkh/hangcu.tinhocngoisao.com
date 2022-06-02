import React, {Component} from 'react';

// import component
import ChooseListProductComponent from './modal/chooseListProductComponent';

class MainComponent extends Component {
    constructor(props) {
        super(props);
        // create event emitter
        window.eventEmitter = new EventEmitter();
    }

    // how to re-render if close and open modal chose product
    componentWillMount() {
        // setup data when create component from input hidden
        let selected_product_value = document.getElementById('selected_product_value').value;
        if (selected_product_value.trim() !== '') {
            selected_product_value = atob(selected_product_value.trim());
            sessionStorage.setItem('list-product-selected', selected_product_value);
        }
    }

    componentWillUnmount() {
        delete(window.eventEmitter);
    }

    _clearProductTypeSelected = (product_types)=> {
        // remove type product is selected from select type option
        const valueProductType = document.getElementById('buildpc-type').value;
        // remove first element
        // copy new variable
        let new_product_types = [...product_types];
        new_product_types.splice(0, 1);
        for(let index in new_product_types) {
            if (new_product_types[index].value === valueProductType) {
                new_product_types.splice(index, 1);
                break;
            }
        }
        return new_product_types;
    }

    _showPopupProduct = ()=> {
        // get type from 
        const valueProductType = document.getElementById('buildpc-type').value;
        if (!valueProductType || valueProductType.trim() === '') {
            alert('Please choose a product type!');
        } else {
            this.props.ToogleModalChooseProduct(true);
            this._setUpData();
        }
    }

    _setUpData = ()=> {
        // init data product type to reducer
        if (typeof product_types !== 'undefined') {
            let new_product_types = this._clearProductTypeSelected(product_types);
            this.props.InitDataProductType(new_product_types);
            // maincomponent chỉ hiện 1 lần duy nhất => init first product type here
            this.props.SelectProductType(new_product_types[0].value);
        } else {
            // console.log('error');
        }
    }

    render() {
        return(
            <React.Fragment>
                <div className="tooltip">
                    Click button to show popup chosse product or edit list product choosed!
                </div>
                <button type="button" onClick={this._showPopupProduct}>Chosse Products</button>
                <h1 className="message">
                    {this.props.action_value.message_close_modal}
                </h1>
                <ChooseListProductComponent />
            </React.Fragment>
        )
    }
}
// create container
import { connect } from 'react-redux';
import {
    SelectProductType,
    ToogleModalChooseProduct,
    InitDataProductType
} from '../action/actionFunction';
import { EventEmitter } from 'events';

const mapStateToProps = state => ({
    action_value : state.ActionReducer
});

const mapDispatchToProps = dispatch => ({
    ToogleModalChooseProduct        : modal_toogle_value => dispatch(ToogleModalChooseProduct(modal_toogle_value)),
    InitDataProductType             : product_types => dispatch(InitDataProductType(product_types)),
    SelectProductType               : product_type_selected => dispatch(SelectProductType(product_type_selected))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(MainComponent);