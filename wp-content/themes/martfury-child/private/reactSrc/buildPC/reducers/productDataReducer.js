
// import variable action
import {
    INIT_PRODUCT_DATA_BY_TYPE
} from '../action/actionType';

/**
 * 
 * @param {*} product_data 
 * @param {*} action 
 */
export const ProductDataReducer = (product_data = {}, action) => {
    /**
     * data {
     *      "cpu" : [],
     *      "ram" : [],
     *      .......
     * }
     */
    var result = {...product_data};
    switch (action.type) {
        case INIT_PRODUCT_DATA_BY_TYPE :
            if (!result.hasOwnProperty(action.data.key)) {
                result[action.data.key] = action.data.value.slice();
            }
            
            return result;
        default :
            return result;
    }
}