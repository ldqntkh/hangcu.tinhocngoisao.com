import React, {Component} from 'react';

import ListProductComponent from './product/listProductComponent';

class RightListProductComponent extends Component {

    render() {
        return(
            <div className="list-product right-content">
                <ListProductComponent position="right"/>
            </div>
        );
    }
}

// create container
import { connect } from 'react-redux';

const mapStateToProps = state => ({
    //action_value : state.ActionReducer
});

const mapDispatchToProps = dispatch => ({
    //ToogleModalChooseProduct        : modal_toogle_value => dispatch(ToogleModalChooseProduct(modal_toogle_value))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(RightListProductComponent);