// import from client app

import {
    ADD_PRODUCT_TO_LIST,
    REMOVE_PRODUCT_FROM_LIST,
    INIT_PRODUCT_TYPE
} from '../action/actionType';
/**
 * list_product = {
 *      "cpu" : [...],
 *      "main" : [...]
 * }
 */
const SaveListProductReducer = function(list_product) {
    // xử lý danh sách product để post lên server
    let lstProduct = JSON.parse(JSON.stringify(list_product));
    for (let key in lstProduct) {
        // skip loop if the property is from prototype
        if (!lstProduct.hasOwnProperty(key)) continue;
    
        let arrObj = lstProduct[key];
        for (let index in arrObj) {
            arrObj[index] = {"id" : arrObj[index].id};
        }
    }
    // xử lý check từng sản phấm trước khi update lại trong storage
    //sessionStorage.setItem('list-product-selected', JSON.stringify(lstProduct));
    let lst_product_selected = sessionStorage.getItem('list-product-selected');
    if (lst_product_selected) {
        lst_product_selected = JSON.parse(lst_product_selected);
        let arrKey1 = Object.keys(lst_product_selected);
        let arrKey2 = Object.keys(lstProduct);
        let arrKey = arrKey1.concat(arrKey2.filter(function (item) {
            return arrKey1.indexOf(item) < 0;
        }));
        for (let i in arrKey) {
            let key = arrKey[i];
            // skip loop if the property is from prototype
            if (!lst_product_selected.hasOwnProperty(key) && !lstProduct.hasOwnProperty(key)) continue;
            if (lst_product_selected.hasOwnProperty(key) && !lstProduct.hasOwnProperty(key)) continue;
            if (!lst_product_selected.hasOwnProperty(key) && lstProduct.hasOwnProperty(key)) {
                lst_product_selected[key] = lstProduct[key];
            } else if (lst_product_selected.hasOwnProperty(key) && lstProduct.hasOwnProperty(key)) {
                let lst1 = lst_product_selected[key];
                let lst2 = lstProduct[key];
                let strLst1 = JSON.stringify(lst1);
                for (let index in lst2) {
                    if (strLst1.indexOf(JSON.stringify(lst2[index])) < 0) {
                        lst1.push(lst2[index]);
                    }
                    if (index > lst1.length) break;
                }
                //lst_product_selected[key] = 
            }
        }
        sessionStorage.setItem('list-product-selected', JSON.stringify(lst_product_selected));
    } else {
        sessionStorage.setItem('list-product-selected', JSON.stringify(lstProduct));
    }
}

const RemveItemFromStorage = function(productID, product_type) {
    let lst_product_selected = sessionStorage.getItem('list-product-selected');
    if (lst_product_selected) {
        lst_product_selected = JSON.parse(lst_product_selected);
        let products = lst_product_selected[product_type];
        for (let index in products) {
            if (products[index].id === productID) {
                products.splice(index, 1);
                break;
            }
        }
        sessionStorage.setItem('list-product-selected', JSON.stringify(lst_product_selected));
    }
}
export const ListProductReducer = (list_product = {
    
}, action)=> {
    let result = {...list_product};
    let listproduct = action.data ?  result[action.data.product_type] : [];
    listproduct = typeof listproduct === 'undefined' ? [] : listproduct;
    switch (action.type) {
        case INIT_PRODUCT_TYPE :
            result[action.data.product_type] = action.data.list_product;
            return result;
        case ADD_PRODUCT_TO_LIST:
            listproduct.unshift(action.data.product)
            result[action.data.product_type] = listproduct;
            SaveListProductReducer(result);
            return result;
        case REMOVE_PRODUCT_FROM_LIST :
            for(let index in listproduct) {
                if (listproduct[index].id === action.data.product.id) {
                    RemveItemFromStorage(listproduct[index].id, action.data.product_type);
                    listproduct.splice(index, 1);
                    break;
                }
            }
            result[action.data.product_type] =  listproduct;
            return result;
        default:
            return list_product;
    }
}