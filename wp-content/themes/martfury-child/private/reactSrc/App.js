// import hotDealApp from './hotDeal/App';
import buildPCApp from './buildPC/App';
import ListCategoryMobile from './listCatagoryMobile/App';
// import WidgetATProduct from './widgetAtProduct/App';
import TableProductSale from './tableProductSale/App';
import InstallmentApp from './installment/App';
import DangKyCodeGameComponent from './dang-ky-code-game/App';
import DangKyBaoHanhComponent from './dang-ky-bao-hanh/App';

const elementIds = [
    {
        ids : ["build-pc-function"],
        appName : buildPCApp
    },
    // {
    //     ids : ["dv-primetime-price-mobile", "dv-primetime-price-desktop"],
    //     appName : hotDealApp
    // },
    {
        ids : ["list_category_mobile"],
        appName : ListCategoryMobile
    },
    {
        ids : ["list_sale_price"],
        appName : TableProductSale
    },
    {
        ids : ["installment"],
        appName : InstallmentApp
    },
    {
        ids : ["dang-ky-code-game"],
        appName : DangKyCodeGameComponent
    },
    {
        ids : ["dang-ky-bao-hanh"],
        appName : DangKyBaoHanhComponent
    }
];

for (let index in elementIds) {
    let item = elementIds[index];
    for (let i in item.ids) {
        if (document.getElementById(item.ids[i])) {
            item.appName();
            break;
        }
    }
}

// function checkWidgetATProduct() {
//     let elementClasses = document.getElementsByClassName('widget_online_shop_wc_products_custom');
//     for (let index in elementClasses) {
//         if (elementClasses[index].id) {
//             WidgetATProduct(elementClasses[index].id);
//         }
//     }
// }
// checkWidgetATProduct();