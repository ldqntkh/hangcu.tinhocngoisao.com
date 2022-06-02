// import variable action
import {
    INIT_DATA_PRODUCT_TYPE,

    TOOGLE_MODAL_CHOOSE_PRODUCT,
    TOOGLE_FILTER_PRODUCT,
    SET_VALUE_PRODUCT_TYPE,
    SET_VALUE_PRODUCT_SEARCH_KEY,
    SET_VALUE_PRODUCT_SEARCH_ATTRIBUTE,
    CLEAN_ALL_ACTION_SEARCH,

    INIT_PRODUCT_DATA_BY_TYPE,

    INIT_COMPUTER_BUILDING_DATA,
    SET_VALUE_COMPUTER_PRODUCT_BY_TYPE,
    CLEAR_VALUE_COMPUTER_PRODUCT_BY_TYPE,
    SET_QUANTITY_COMPUTER_PRODUCT_BY_TYPE,
    RESET_VALUE_COMPUTER_PRODUCT
} from './actionType';

//---------------Product type function------------------
export const InitDataProductType = data_product_type => ({
    type : INIT_DATA_PRODUCT_TYPE,
    data : data_product_type
});

//----------------Product data---------------------------
export const InitProductDataByType = product_data_by_type => ({
    type: INIT_PRODUCT_DATA_BY_TYPE,
    data : product_data_by_type
});


//----------------Action app----------------------------
export const ToogleModalChooseProduct = toogle_value => ({
    type : TOOGLE_MODAL_CHOOSE_PRODUCT,
    data : toogle_value
});

export const ToogleFilterProduct = () => ({
    type : TOOGLE_FILTER_PRODUCT,
    data : null
});

export const SetValueProductType = product_type => ({
    type : SET_VALUE_PRODUCT_TYPE,
    data : product_type
});

export const SetValueProductSearchKey = product_search_key => ({
    type : SET_VALUE_PRODUCT_SEARCH_KEY,
    data : product_search_key
});

export const SetValueProductSearchAttribute = product_search_attributes => ({
    type : SET_VALUE_PRODUCT_SEARCH_ATTRIBUTE,
    data : product_search_attributes
});

export const CleanValueProductSearchKey = () => ({
    type : CLEAN_ALL_ACTION_SEARCH,
    data : null
});


//----------------Computer building data----------------
export const InitComputerbuildingData = computer_building_data => ({
    type : INIT_COMPUTER_BUILDING_DATA,
    data : computer_building_data
});

export const SetValueComputerProductByType = computer_product_type => ({
    type : SET_VALUE_COMPUTER_PRODUCT_BY_TYPE,
    data : computer_product_type
});

export const ClearValueComputerProductByType = computer_product_type => ({
    type : CLEAR_VALUE_COMPUTER_PRODUCT_BY_TYPE,
    data : computer_product_type
});

export const SetQuantityOfComputerProduct = computer_product_data => ({
    type : SET_QUANTITY_COMPUTER_PRODUCT_BY_TYPE,
    data : computer_product_data
});

export const ResetValueBuildPC = () => ({
    type: RESET_VALUE_COMPUTER_PRODUCT
})
