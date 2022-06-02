'use strict';
var productType = require('./product_type/product_type');
var attributes = require('./attribute/attributes');
var group_attribute = require('./attribute/group_attribute');
var group_product_attribute = require('./attribute/group_product_attribute');
var product_data_compare = require('./data_compare/product_data_compare');
var product_slider_media = require('./media/mediaLibrary');
var select_attribute_specifications = require('./attribute/attributes_specifications');
var cachingCompareData = require('./caching/create-caching-data');

jQuery(document).ready(function () {
    productType.init();
    attributes.init();
    group_attribute.init();
    group_product_attribute.init();
    product_data_compare.init();
    product_slider_media.init();
    select_attribute_specifications.init();
    cachingCompareData.init();
});