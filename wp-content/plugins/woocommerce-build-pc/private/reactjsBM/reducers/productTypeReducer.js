// import from client app
import {
    INIT_DATA_PRODUCT_TYPE,
} from '../action/actionType';

export const ProductTypeReducer = (producttype_data = {
    product_types : []
}, action)=> {
    let result = {...producttype_data};
    switch (action.type) {
        case INIT_DATA_PRODUCT_TYPE:
            result['product_types'] = action.data;
            return result;
        default:
            return producttype_data;
    }
}