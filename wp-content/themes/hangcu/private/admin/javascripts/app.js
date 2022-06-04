'use strict';

import categories from './categories/categories';
import homepageconfig from './home/homepage';
import Order from './order/order';

jQuery(document).ready(function () {
    categories.init();
    homepageconfig.init();
    Order.init();
});