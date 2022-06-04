'use strict';

import Share from './share/share-function';
import ProductListpage from './product/productListpage';
import ProductDetail from './product/product_details';
import SimpleAddToCartHandler from './product/simple-add-to-cart';

import paymentCheckout from './checkout/payment';
import checkoutAddress from './checkout/address';
import checkout from './checkout/checkout';

import ProductCompare from './product/product-compare-carausel';

import MyAddress from './account/my-address';
import myOrder from './account/my-order';
import MyAccount from './account/my-account';
import editAccount from './account/edit-account';
import account from './account/account';

import Cart from './cart/cart';

jQuery(document).ready(function () {
    Share.init();
    account.init();
    ProductListpage.init();
    ProductDetail.init();
    SimpleAddToCartHandler.init();
    paymentCheckout.init();
    checkoutAddress.init();
    checkout.init();
    MyAddress.init();
    myOrder.init();
    MyAccount.init();
    editAccount.init();
    Cart.init();
    ProductCompare.init();
});