import React, {Component} from 'react';

class HeaderComponent extends Component {

    constructor(props) {
        super(props);
    }

    calculationPrice = ()=> {
        let {computer_building_data} = this.props;
        let total_price = 0;
        
        localStorage.setItem('computer_building_data', JSON.stringify(computer_building_data));

        for(let index in computer_building_data) {
            if (computer_building_data[index].product !== null) {
                let item = computer_building_data[index];
                let price = item.product.sale_price !== "0" ? item.product.sale_price : item.product.regular_price;
                let quantity = item.quantity
                total_price += price * quantity;
            }
        }
        return total_price;
    }
    rebuildPC = ()=> {
        this.props.ResetValueBuildPC();
    }

    render() {
        return(
            <div className="build-pc-header">
                <div className="left-content">
                    <button className="re-build" onClick={this.rebuildPC}>
                        <i className="fa fa-refresh"></i>
                        Xây dựng lại
                    </button>
                </div>
                <div className="right-content">
                    <span>Chi phí dự tính: <strong>{this.calculationPrice().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}đ</strong></span>
                </div>
            </div>
        );
    }
}

// create container
import { connect } from 'react-redux';

import {
    ResetValueBuildPC
} from '../../action/actionFunction';

const mapStateToProps = state => ({
    computer_building_data : state.ComputerBuildingDataReducer
});

const mapDispatchToProps = dispatch => ({
    ResetValueBuildPC : () => dispatch(ResetValueBuildPC())
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(HeaderComponent);