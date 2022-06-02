
// import variable action
import {
    INIT_COMPUTER_BUILDING_DATA,
    SET_VALUE_COMPUTER_PRODUCT_BY_TYPE,
    SET_QUANTITY_COMPUTER_PRODUCT_BY_TYPE,
    CLEAR_VALUE_COMPUTER_PRODUCT_BY_TYPE,
    RESET_VALUE_COMPUTER_PRODUCT
} from '../action/actionType';

/**
 * data có dạng
 * [
 *      "main" : {
 *          "product" : product,
 *          "quantity" : 1,
 *          "require" : true,
 *          "require-by" : null,
 *          "link" : null
 *      }, .....
 * ]
 */

export const ComputerBuildingDataReducer = (computer_building_data = {}, action) => {
    let result = [];
    switch (action.type) {
        case INIT_COMPUTER_BUILDING_DATA :
            result = {...action.data};
            return result;
        case SET_VALUE_COMPUTER_PRODUCT_BY_TYPE:
            result = {...computer_building_data};
            result[action.data.type]["product"] = action.data.value;
            return result;
        case CLEAR_VALUE_COMPUTER_PRODUCT_BY_TYPE:
            result = {...computer_building_data};
            result[action.data.type]["product"] = null;
            result[action.data.type]["quantity"] = 1;
            return result;
        case SET_QUANTITY_COMPUTER_PRODUCT_BY_TYPE:
            result = {...computer_building_data};
            result[action.data.type]["quantity"] = action.data.value;
            return result;
        case RESET_VALUE_COMPUTER_PRODUCT:
            result = {...computer_building_data};
            for(let index in result) {
                result[index]["product"] = null;
                result[index]["quantity"] = 1;
            }
            return result;
        default :
            return computer_building_data;
    }
}