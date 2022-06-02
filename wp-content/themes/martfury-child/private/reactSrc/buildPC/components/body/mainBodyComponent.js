import React, {Component} from 'react';

// import component
import ProductTypeItemComponent from '../body/productType/productTypeItemComponent';
import ChooseProductComponent from '../body/productModal/chooseProductComponent';

class MainBodyComponent extends Component {

    constructor(props) {
        super(props);
    }
    

    render() {
        let {data_product_type} = this.props;
        let listProductType =[];
        if (data_product_type !== undefined) {
            for(let index in data_product_type) {
                listProductType.push(
                    <ProductTypeItemComponent key={index} product_type={data_product_type[index]} index={parseInt(index) + 1}/>
                );
            }
        }

        return(
            <React.Fragment>
                <div className="build-pc-body">
                    {listProductType}
                </div>
                <ChooseProductComponent />
            </React.Fragment>
        );
    }
}

// create container
import { connect } from 'react-redux';
import {
    //InitDataProductType
} from '../../action/actionFunction';

const mapStateToProps = state => ({
    data_product_type : state.ProductTypeReducer
});

const mapDispatchToProps = dispatch => ({
    
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(MainBodyComponent);