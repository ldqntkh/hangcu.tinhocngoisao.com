'use strict';


const $ = jQuery;
const Se_Group_Products = {
    group_controller : null,
    xhrRequestSearch : null,
    xhrRequestGetProducts : null,
    data_products : null,
    data_products_display: [],

    init : function (parent) {
        Se_Group_Products.group_controller = parent;

        $('body').on('keyup', '#se-search-group-name', Se_Group_Products.searchProducts);
        $('body').on('click', '#close-search', function() {
            Se_Group_Products.data_products = null;
            $('.search_group #list-products').remove();
            $('#se-search-group-name').val('');
            $(this).remove();
        });

        $('body').on('click', '#list-products .item', Se_Group_Products.setProductGroup);
        
        $('body').on('click', '#list-product-selected .delete', Se_Group_Products.removeProduct);
        $('body').on('click', '#list-product-selected .move_up', Se_Group_Products.moveUpSEGroup );
        $('body').on('click', '#list-product-selected .move_down', Se_Group_Products.moveDownSEGroup );

        $('body').on( 'click', '#update-lst-product', Se_Group_Products.updateListProduct )
    },

    searchProducts : function(e) {
        var keysearch = e.target.value.trim();
        
        if ( keysearch == '' || keysearch.length < 3 ) return;

        if ( Se_Group_Products.xhrRequestSearch != null ) {
            Se_Group_Products.xhrRequestSearch.abort();
            Se_Group_Products.xhrRequestSearch = null;
        }


        Se_Group_Products.xhrRequestSearch = jQuery.ajax({
            type: 'get',
            url: se_admin_ajax,
            data: {
                action: 'se_search_product',
                fn : 'get_ajax_search',
                term : keysearch
            },
            beforeSend: function () {
                $('.search_group span.spinner').addClass( 'is-active' );
            },
            success: function (response) {
                if (response.success) {
                    var products = response.data.products;
                    Se_Group_Products.data_products = products;

                    Se_Group_Products.showListProduct(Se_Group_Products.data_products);
                } else {
                    Se_Group_Products.showListProduct(null);
                }
            },
            error: function (response, errorStatus, errorMsg) {
               //console.log( response )
            },
            complete : function (data) {
                Se_Group_Products.xhrRequestSearch.abort();
                Se_Group_Products.xhrRequestSearch = null;
                $('.search_group span.spinner').removeClass( 'is-active' );
            }
        });
    },

    showListProduct: function ( products = null ) {
        
        if ( products && products.length > 0 ) {
            var html = '<button type="button" id="close-search">X</button>';
            html += '<div id="list-products">';
            for( var i = 0; i< products.length; i++ ) {
                var item = products[i];
                html += `<div class="item" data-pr-id="${i}">
                            <img src="${item.image}" />
                            <div class="pr-detail">
                                <p>${item.name}</p>
                                <p>Giá: ${item.price}</p>
                            </div>
                        </div>`;
            }

            html += '</div>';
            $('.search_group #list-products').remove();
            $('.search_group').append(html);

        } else {
            $('.search_group #list-products').remove();
        }
    },

    setProductGroup: function (e) {

        if ( Se_Group_Products.data_products_display.length >= 8 ) {
            alert('Mỗi nhóm chúng tôi chỉ hỗ trợ tối đa 8 sản phẩm!');
            return;
        }

        var index = $(this).attr('data-pr-id');
        if ( Se_Group_Products.data_products == null || Se_Group_Products.length < index ) return;

        var item = Se_Group_Products.data_products[index];
        
        if ( !Se_Group_Products.checkProductIdSelected( item.ID ) ) {

            if ( Se_Group_Products.group_controller.itemSelected ) {

                if ( !typeof item === 'undefined' || !Se_Group_Products.group_controller.itemSelected.products || Se_Group_Products.group_controller.itemSelected.products.indexOf( item.ID ) < 0 ) {

                    // Se_Group_Products.group_controller.itemSelected.products.push(item.ID);
                
                    // Se_Group_Products.group_controller.updateSelectedData();
    
                    Se_Group_Products.data_products_display.push( item );
    
                    Se_Group_Products.renderListProductSelected( Se_Group_Products.data_products_display );
                }
            }
        }
        
        $(this).remove();
    },

    renderListProductSelected: function ( products = null ) {
        if ( products !== null && products.length > 0 ) {
            var html = '';
            
            for( var i = 0; i< products.length; i++ ) {
                var item = products[i];
                html += `<div class="item" data-pr-id="${i}">
                            <span class="delete"></span>
                            <div class="content">
                                <img src="${item.image}" />
                                <div class="pr-detail">
                                    <p>${item.name}</p>
                                    <p>Giá: ${item.price}</p>
                                </div>
                            </div>
                            <span class="move move_up"></span>
                            <span class="move move_down"></span>
                        </div>`;
            }
            $('#list-product-selected').html('');
            $('#list-product-selected').html(html);
        } else {
            $('#list-product-selected').html('');
        }
    },

    renderListProductSelectedIDs: function ( itemSelected ) {
        if ( Se_Group_Products.xhrRequestGetProducts != null ) return;
        var ids = itemSelected.products;
        if ( ids == null || ids.length <= 0 ) {
            Se_Group_Products.renderListProductSelected(null);
            return;
        };
        
        Se_Group_Products.xhrRequestGetProducts = jQuery.ajax({
            type: 'POST',
            url: se_admin_ajax,
            data: {
                action: 'se_get_product_data',
                fn : 'post_ajax_search',
                ids : ids
            },
            beforeSend: function () {
                jQuery('body').addClass('gearvn_loading');
            },
            success: function (response) {
                if (response.success) {
                    var products = response.data.products;
                    Se_Group_Products.data_products = products;
                    Se_Group_Products.data_products_display = products;
                    Se_Group_Products.renderListProductSelected( Se_Group_Products.data_products_display );
                } else {
                    Se_Group_Products.renderListProductSelected(null);
                }
            },
            error: function (response, errorStatus, errorMsg) {
               //console.log( response )
            },
            complete : function (data) {
                Se_Group_Products.xhrRequestGetProducts.abort();
                Se_Group_Products.xhrRequestGetProducts = null;
                jQuery('body').removeClass('gearvn_loading');
            }
        });
    },

    removeProduct: function (e) {
        let index = $($(this).parent()).attr('data-pr-id');
        if ( typeof index == 'undefined' || index == null ) return;
        
        Se_Group_Products.data_products_display.splice(index,1);

        if ( Se_Group_Products.group_controller.itemSelected ) {
            // Se_Group_Products.group_controller.itemSelected.products.splice(index,1);
            
            // Se_Group_Products.group_controller.updateSelectedData();
            Se_Group_Products.renderListProductSelected( Se_Group_Products.data_products_display );
        }
    },

    resetData: function( ) {
        Se_Group_Products.xhrRequestSearch = null;
        Se_Group_Products.xhrRequestGetProducts = null;
        Se_Group_Products.data_products = null;
        Se_Group_Products.data_products_display= [];
        Se_Group_Products.renderListProductSelected(null);
    },

    checkProductIdSelected: function (id) {
        for( let index in Se_Group_Products.group_controller.group_values ) {
            if ( Se_Group_Products.group_controller.group_values[index].products == null
                || Se_Group_Products.group_controller.group_values[index].products.length == 0 ) return false;
            if ( Se_Group_Products.group_controller.group_values[index].products.indexOf(id) > -1 ) return true;
        }
        return false;
    },

    moveUpSEGroup: function(e) {
        let index = parseInt($($(this).parent()).attr('data-pr-id'));
        if( index == null ) index = 0;
        if ( typeof index == 'undefined' || index == null ) return;
        if ( index == 0 ) return;

        let item = Se_Group_Products.data_products_display[index];
        Se_Group_Products.data_products_display[index] = Se_Group_Products.data_products_display[index-1];
        Se_Group_Products.data_products_display[index-1] = item;

        let arrPrs = [];
        for( let i in Se_Group_Products.data_products_display ) {
            arrPrs.push( Se_Group_Products.data_products_display[i].ID );
        }
        // Se_Group_Products.group_controller.itemSelected.products = arrPrs;
        // Se_Group_Products.group_controller.updateSelectedData();
        Se_Group_Products.renderListProductSelected(  Se_Group_Products.data_products_display );
    },

    moveDownSEGroup: function(e) {
        let index = parseInt($($(this).parent()).attr('data-pr-id'));
        if( index == null ) index = 0;

        if ( typeof index == 'undefined' || index == null ) return;
        if ( index == Se_Group_Products.data_products_display.length -1 ) return;

        let item = Se_Group_Products.data_products_display[index];
        Se_Group_Products.data_products_display[index] = Se_Group_Products.data_products_display[index + 1];
        Se_Group_Products.data_products_display[index + 1] = item;
        

        let arrPrs = [];
        for( let i in Se_Group_Products.data_products_display ) {
            arrPrs.push( Se_Group_Products.data_products_display[i].ID );
        }
        // Se_Group_Products.group_controller.itemSelected.products = arrPrs;
        // Se_Group_Products.group_controller.updateSelectedData();
        Se_Group_Products.renderListProductSelected(  Se_Group_Products.data_products_display );
    },

    updateListProduct: function(e) {
        if ( Se_Group_Products.xhrRequestGetProducts != null ) return;

        let arrPrs = [];
        for( let i in Se_Group_Products.data_products_display ) {
            arrPrs.push( Se_Group_Products.data_products_display[i].ID );
        }
        if ( arrPrs.length <= 0 ) return;


        Se_Group_Products.xhrRequestGetProducts = jQuery.ajax({
            type: 'POST',
            url: se_admin_ajax,
            data: {
                action: 'se_update_product_data',
                group_id: Se_Group_Products.group_controller.itemSelected.ID,
                ids : arrPrs
            },
            beforeSend: function () {
                jQuery('body').addClass('gearvn_loading');
            },
            success: function (response) {
                if (response.success) {
                    Se_Group_Products.group_controller.itemSelected.products = arrPrs;
                } else {
                    Se_Group_Products.renderListProductSelected(null);
                }
            },
            error: function (response, errorStatus, errorMsg) {
               //console.log( response )
            },
            complete : function (data) {
                Se_Group_Products.xhrRequestGetProducts.abort();
                Se_Group_Products.xhrRequestGetProducts = null;
                jQuery('body').removeClass('gearvn_loading');
            }
        });
    }
}

module.exports = Se_Group_Products;