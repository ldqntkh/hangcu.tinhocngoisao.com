import React, {Component} from 'react';


// import component
import MainBodyComponent from './body/mainBodyComponent';
import HeaderComponent from './header/headerComponent';
import FooterComponent from './footer/FooterComponent';

class MainComponent extends Component {

    constructor(props) {
        super(props);
    }

    componentWillMount() {
        if (typeof product_types !== 'undefined') {
            this.props.InitDataProductType(product_types);
            
            if (localStorage.getItem('computer_building_data') && typeof edit_building_data === 'undefined') {
                this.props.InitComputerbuildingData(JSON.parse(localStorage.getItem('computer_building_data')));
            } else {
                let result = [];
                let product = null;
                let quantity = 1;
                for(let index in product_types) {
                    if (typeof edit_building_data !== 'undefined' && edit_building_data[product_types[index].value]) {
                        product = edit_building_data[product_types[index].value].product;
                        quantity = edit_building_data[product_types[index].value].quantity;
                    }
                    switch(product_types[index].value) {
                        case "main":
                            result['main'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "cpu":
                            result['cpu'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "require-field" : product_types[index]["require-field"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "ram":
                            result['ram'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "require-field" : product_types[index]["require-field"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "ssd":
                            result['ssd'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "require-field" : product_types[index]["require-field"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "hdd":
                            result['hdd'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "optane":
                            result['optane'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "vga":
                            result['vga'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "power":
                            result['power'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "case":
                            result['case'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "radiator":
                            result['radiator'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "screen":
                            result['screen'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "keyboard":
                            result['keyboard'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "mouse":
                            result['mouse'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "headphone":
                            result['headphone'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "link" : product_types[index].link
                            };
                            break;
                        case "fancase":
                            result['fancase'] = {
                                "product" : product,
                                "quantity" : quantity,
                                "require" : product_types[index].require,
                                "require-by" : product_types[index]["require-by"],
                                "link" : product_types[index].link
                            };
                            break;
                    }
                    product = null;
                    quantity = 1;
                }
                this.props.InitComputerbuildingData(result);
            }
        }
    }

    render() {
        return(
            <React.Fragment>
                <HeaderComponent />
                <MainBodyComponent />
                <FooterComponent />
            </React.Fragment>
        );
    }
}

// Create container
import { connect } from 'react-redux';
import {
    InitDataProductType,
    InitComputerbuildingData
} from '../action/actionFunction';

const mapStateToProps = state => ({
    data_product_type : state.ProductTypeReducer
});

const mapDispatchToProps = dispatch => ({
    InitDataProductType         : data_product_type => dispatch(InitDataProductType(data_product_type)),
    InitComputerbuildingData    : computer_building_data => dispatch(InitComputerbuildingData(computer_building_data))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(MainComponent);