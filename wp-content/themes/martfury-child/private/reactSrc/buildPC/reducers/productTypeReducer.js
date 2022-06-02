
// import variable action
import {
    INIT_DATA_PRODUCT_TYPE
} from '../action/actionType';


export const ProductTypeReducer = (data_product_type = [], action) => {
    let result = [...data_product_type];
    switch (action.type) {
        case INIT_DATA_PRODUCT_TYPE :
            result = [...action.data];
            return result;
        default :
            return data_product_type;
    }
}