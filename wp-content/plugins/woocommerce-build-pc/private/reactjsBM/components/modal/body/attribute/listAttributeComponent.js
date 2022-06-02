import React, {Component} from 'react';
import AttributeItemComponent from './attributeItemComponent';

class ListAttributeComponent extends Component {
    constructor(props) {
        super(props);
        if (window.eventEmitter) {
            this.state = {
                lstProduct : []
            }
            window.eventEmitter.on('showListAttribute', (lstProduct) => {
                this.setState({
                    lstProduct : lstProduct
                })
            });
        }
    }

    isEmpty = (obj)=> {
        for(var prop in obj) {
            if(obj.hasOwnProperty(prop))
                return false;
        }
    
        return JSON.stringify(obj) === JSON.stringify({});
    };

    toggleFilter = ()=> {
        this.props.ToogleFilterProduct();
    }

    findListAttribute = ()=> {
        let {lstProduct} = this.state;
        let require_field = 'pa_';
        let regex = null;
        let arrAttributes = {};
        for(let index in lstProduct) {
            let attributes = lstProduct[index]['attributes'];
            if (regex !== null && attributes && !regex.test(JSON.stringify(attributes).toLowerCase())) {
                delete lstProduct[index];
                continue;
            }
            for(let i in attributes) {
                let name = attributes[i]['name'];
                let fullname = attributes[i]['full_name'];
                if (name === require_field) continue;
                let values = attributes[i]['values'];

                //
                if (arrAttributes.hasOwnProperty(name)) {
                    // xác định số lượng mỗi attr có bao nhiêu product
                    
                    for(let k in values) {
                        let flag = false;
                        for(let index in arrAttributes[name]) {
                            if (arrAttributes[name][index]['slug'] === values[k]['slug']) {
                                flag = true;
                                arrAttributes[name][index]['count'] = ++arrAttributes[name][index]['count'];
                                break;
                            }
                        }
                        if (!flag) {
                            arrAttributes[name][arrAttributes[name].length] = {
                                "group" : name,
                                "full_name" : fullname,
                                "name" : values[k]['name'],
                                'slug' : values[k]['slug'],
                                "count" : 1
                            };  
                        }
                        
                    }
                    
                } else {
                    let arrValue = [];
                    for(let k in values) {
                        arrValue.push({
                            "group" : name,
                            "full_name" : fullname,
                            "name" : values[k]['name'],
                            'slug' : values[k]['slug'],
                            "count" : 1
                        });
                    }
                    arrAttributes[name] = arrValue;
                }
            }
        }
        return arrAttributes;
    }

    renderListAttributes = (listAttribute)=> {
        if (!this.isEmpty(listAttribute)) {
            let result  = [];
            let index = 0;
            for (var prop in listAttribute) {
                if(!listAttribute.hasOwnProperty(prop)) continue;
                result.push(<AttributeItemComponent attribute_name={prop} attribute_value={listAttribute[prop]} key={index} />);
                index ++;
            }
            return result;       
        }
        return null;
    }

    render() {
        let listAttribute = this.findListAttribute();
        return(
            <div className="list-attris">
                <div className="list-attributes">
                    {this.renderListAttributes(listAttribute)}
                </div>
            </div>
        );
    }
}


// create container
import { connect } from 'react-redux';


const mapStateToProps = state => ({
    product_type_selected : state.ActionReducer.product_type_selected,
    list_product_reducer : state.ListProductReducer
});

const mapDispatchToProps = dispatch => ({
    //ToogleModalChooseProduct        : modal_toogle_value => dispatch(ToogleModalChooseProduct(modal_toogle_value))
});

export default connect(
    mapStateToProps,
    mapDispatchToProps
)(ListAttributeComponent);