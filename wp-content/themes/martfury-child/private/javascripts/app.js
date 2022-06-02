import paymentCheckout from './checkout/payment';
import checkoutAddress from './checkout/address';
import checkout from './checkout/checkout';
import homePost from './home/post-component';
import singleProduct from './product/single-product';
import product_compare_carausel from './product/product-compare-carousel';

jQuery(document).ready(function () {
    checkout.init();
    paymentCheckout.init();
    checkoutAddress.init();
    homePost.init();
    singleProduct.init();
    product_compare_carausel.init();
});