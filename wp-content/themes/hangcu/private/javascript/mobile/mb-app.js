'use strict';

import mbHeader from './mb-header';
import mbFilter from './mb-filter';
import mbCart from './mb-cart';
import mbCheckout from './mb-checkout';

jQuery(document).ready(function () {
    mbHeader.init();
    mbFilter.init();
    mbCart.init();
    mbCheckout.init();
});