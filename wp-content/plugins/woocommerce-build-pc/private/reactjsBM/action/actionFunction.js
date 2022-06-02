import {
    SELECT_PRODUCT_TYPE,
    ADD_PRODUCT_TO_LIST,
    REMOVE_PRODUCT_FROM_LIST,
    INIT_PRODUCT_TYPE,
    INIT_MESSAGE_CLOSE_MODAL,
    TOOGLE_MODAL_CHOOSE_PRODUCT,
    INIT_DATA_PRODUCT_TYPE,
    SET_VALUE_PRODUCT_SEARCH_ATTRIBUTE
} from './actionType';

//---------------Product type function------------------
export const SelectProductType = product_type_selected => ({
    type : SELECT_PRODUCT_TYPE,
    data : product_type_selected
});

//----------------Add product to list--------------------
export const AddProductToList = data => ({
    type : ADD_PRODUCT_TO_LIST,
    data
});

//----------------remove product from list--------------------
export const RemoveProductFromList = data => ({
    type : REMOVE_PRODUCT_FROM_LIST,
    data
});

//----------------init product to reducer--------------------
export const InitProductToReducer = data => ({
    type : INIT_PRODUCT_TYPE,
    data
});

//----------------init product to reducer--------------------
export const InitMessageCloseModal = message => ({
    type : INIT_MESSAGE_CLOSE_MODAL,
    message
});

//----------------Action app----------------------------
export const ToogleModalChooseProduct = toogle_value => ({
    type : TOOGLE_MODAL_CHOOSE_PRODUCT,
    data : toogle_value
});

//---------------Product type function------------------
export const InitDataProductType = data_product_type => ({
    type : INIT_DATA_PRODUCT_TYPE,
    data : data_product_type
});

//---------------SetValueProductSearchAttribute function------------------
export const SetValueProductSearchAttribute = product_search_attributes => ({
    type : SET_VALUE_PRODUCT_SEARCH_ATTRIBUTE,
    data : product_search_attributes
});