import React, {Component} from 'react';

import ListAttributeComponent from './attribute/listAttributeComponent';
import ListProductComponent from './product/listProductComponent';

class LeftListProductComponent extends Component {
    constructor(props) {
        super(props);
    }
    render() {
        return(
            <div className="list-product left-content">
                <ListAttributeComponent />
                <ListProductComponent position="left"/>
            </div>
        );
    }
}

/**
 * mỗi khi chọn 1 product type from header (change product type selected)
 * load lại component theo type
 * check reducer đã có list product chưa -> not thì load từ api về
 * thiết kế mới component product
 * đối với left list product sẽ có thêm phần render list attributes
 */

// create container
import { connect } from 'react-redux';

// import {
//     ToogleModalChooseProduct
// } from '../../../reactjs/action/actionFunction';

const mapStateToProps = state => ({
    //action_value : state.ActionReducer
});

const mapDispatchToProps = dispatch => ({
    //ToogleModalChooseProduct        : modal_toogle_value => dispatch(ToogleModalChooseProduct(modal_toogle_value))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(LeftListProductComponent);