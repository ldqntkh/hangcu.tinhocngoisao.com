'use strict';

jQuery(window).load(function() {
    if ( typeof productSaleAccessories == 'undefined' || typeof productSelected == 'undefined' ) {
        // return;
    } else {
        saleAccessotiesController.init();
    }
});

const saleAccessotiesController = {
    newProductSelected: null,
    keySelected : null,
    productIdSelected: null,
    parentSelected: null,
    xhrRequest: null,
    init: function() {
        saleAccessotiesController.newProductSelected = productSelected;
        
        jQuery('body').on('click', '.choose-sale-product', function(e) {
            e.preventDefault();
            var data_name = jQuery(this).attr('data-name');
            var parent = jQuery(this).closest('li.product');
            saleAccessotiesController.parentSelected = parent;
            saleAccessotiesController.keySelected = data_name;
            saleAccessotiesController.showPopupChooseProduct( data_name );
        });

        jQuery('body').on('click', '.select-product-id', function() {
            saleAccessotiesController.updateProductSelected(this);
        });

        jQuery('body').on('change', '.product-check', function() {
            saleAccessotiesController.updatePriceTotal();
        } );

        jQuery('body').on('click', '.add-all-to-cart', function() {
            saleAccessotiesController.addAllProductToCart();
        });

        jQuery('body').on('click', '#close-modal', function() {
            jQuery('#modalShowAccessories').modal('hide');
        })
    },

    showPopupChooseProduct: function ( key ) {
        
        for( let index in productSaleAccessories ) {
            if ( productSaleAccessories[index]['name'] == key ) {
                
                // set name
                jQuery('#accessories-title').text( key );
                // set product to modal

                var items = productSaleAccessories[index]['products'];
                var html = '<ul>';
                
                // get array key
                var arrKeySelected = saleAccessotiesController.newProductSelected.map( function(item) { return item['product_id'] }  );

                for( let i in items ) {
                    let item = items[i];
                    var flag =  arrKeySelected.indexOf( item['product_id'] ) > -1 ? true : false;
                    if ( flag ) saleAccessotiesController.productIdSelected = item['product_id'];
                    html += `<li>
                                <img src="${item['image']}" alt="">
                                <h3>${item['product_name']}</h3>
                                <div class="price-add-to-cart">
                                    <span class="accessories-price">
                                        <ins>
                                            <span class="woocommerce-Price-amount amount">${item['product_price_display']}</span>
                                        </ins>
                                        <span style="display: flex; justify-content: flex-start; align-items: center;">
                                            <del>
                                                <span class="woocommerce-Price-amount amount">${item['product_sale_price_display']}</span>
                                            </del>
                                            <span style="font-size: 12px;">&nbsp;(- ${item['product_percent_discount']}%)</span>
                                        </span>
                                    </span>
                                </div>
                                <span class="${ flag ? '' : 'select-product-id' }" data-product-id="${item['product_id']}">${ flag ? 'Đang chọn' : 'Chọn mua' }</span>
                            </li>`;
                }

                html += '</ul>';
                jQuery('#accessories-lst-products').html( html );
                jQuery('#modalShowAccessories').modal('show');
            }
        }
        return;
    },

    updateProductSelected: function( e ) {
        let product_select = jQuery(e).attr('data-product-id');
        product_select = parseInt(product_select);
        for( let index in productSaleAccessories ) {
            if ( productSaleAccessories[index]['name'] == saleAccessotiesController.keySelected ) {
                let products = productSaleAccessories[index]['products'];
                for( let i in products ) {
                    if ( products[i]['product_id'] == product_select ) {

                        // delete( saleAccessotiesController.newProductSelected[saleAccessotiesController.productIdSelected] );
                        let _index_key = 0;
                        for( let i in saleAccessotiesController.newProductSelected ) {
                            if ( saleAccessotiesController.newProductSelected[i]['product_id'] == saleAccessotiesController.productIdSelected ) {
                                _index_key = i;
                                break
                            }
                        }
                        
                        saleAccessotiesController.newProductSelected[_index_key] = {
                            'product_id': product_select,
                            'quantity'  : 1,
                            'price'     : products[i]['product_price'],
                            'price_discount'    : products[i]['product_price_discount']
                        };
                        saleAccessotiesController.keySelected = null;
                        saleAccessotiesController.productIdSelected = null;

                        // update product selected
                        let parent = jQuery( saleAccessotiesController.parentSelected );
                        jQuery( parent.find('img')[0] ).attr('src', products[i]['image'] );
                        jQuery( parent.find('h2')[0] ).text( products[i]['product_name'] );
                        jQuery( parent.find('.accessories-price')[0] ).html(
                            `<ins>
                                <span class="woocommerce-Price-amount amount">${products[i]['product_price_display']}</span>
                            </ins>
                            <span style="display: flex; justify-content: flex-start; align-items: center;">
                                <del>
                                    <span class="woocommerce-Price-amount amount">${products[i]['product_sale_price_display']}</span>
                                </del>
                                <span style="font-size: 12px;">&nbsp;(- ${products[i]['product_percent_discount']}%)</span>
                            </span>`
                        );
                        saleAccessotiesController.updatePriceTotal();
                        jQuery('#modalShowAccessories').modal('hide');
                        return;
                    }
                }
                break;
            }
        }
    },

    updatePriceTotal: function() {
        var lstItems = jQuery('#sale-accessories-lst-product').find('li');
        var total_product = 0,
            total_price = 0,
            total_discount = 0;

        for( let i= 0 ; i < saleAccessotiesController.newProductSelected.length; i++ ) {
            if ( lstItems[i] ) {
                let item = saleAccessotiesController.newProductSelected[i];
                let elementItem = jQuery( lstItems[i] );

                if( elementItem.find('.product-check') && elementItem.find('.product-check').length == 1 ) {
                    if ( !jQuery( elementItem.find('.product-check')[0] ).is(":checked") ) continue;
                } 
                
                total_product++;
                total_price += parseInt(item['price']);
                total_discount += parseInt(item['price_discount']);
            }
        }

        let html = `<h6>Tổng tiền</h6>
                    <div class="total-price">
                        <span class="total-price-html">
                            <span class="woocommerce-Price-amount amount">${total_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}
                            <span class="woocommerce-Price-currencySymbol">₫</span></span></span> cho <span class="total-products">${total_product}</span> sản phẩm
                    </div>
                    <div class="accessories-add-all-to-cart">
                        <button type="button" class="single_add_to_cart_button button btn btn-primary add-all-to-cart">
                            Mua ${total_product} sản phẩm<br>
                            <span>Tiết kiệm <span class="woocommerce-Price-amount amount">${total_discount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}<span class="woocommerce-Price-currencySymbol">₫</span></span></span>
                        </button>
                    </div>`;
        jQuery('#total-add-all-to-cart').html( html );
    },

    addAllProductToCart: function() {

        if ( saleAccessotiesController.xhrRequest != null ) return;
        var lstItems = jQuery('#sale-accessories-lst-product').find('li');
        
        var product_data = '';
        for( let i= 0 ; i < saleAccessotiesController.newProductSelected.length; i++ ) {
            if ( lstItems[i] ) {
                let item = saleAccessotiesController.newProductSelected[i];
                let elementItem = jQuery( lstItems[i] );

                if( elementItem.find('.product-check') && elementItem.find('.product-check').length == 1 ) {
                    if ( !jQuery( elementItem.find('.product-check')[0] ).is(":checked") ) continue;
                } 
                if ( product_data != '' ) product_data += ',';
                product_data += item['product_id'] + '_' + 1;
            }
        }
        let add_to_cart_error = false;
        saleAccessotiesController.xhrRequest = jQuery.ajax({
            type: 'get',
            url: gearvn_accessries_ajax,
            data: {
                action: 'insert_multiple_products_to_cart',
                product_data_add_to_cart: product_data
            },
            beforeSend: function () {
                jQuery('body').addClass('gearvn_loading');
            },
            success: function (response) {
                window.location.reload();
                // if( response.error ) {
                //     add_to_cart_error = true;
                // } else {
                //     window.location.reload();
                // }
                // saleAccessotiesController.accessory_refresh_fragments( response );
            },
            error: function (response, errorStatus, errorMsg) {//console.log( response )
            },
            complete: function (data) {
                saleAccessotiesController.xhrRequest.abort();
                saleAccessotiesController.xhrRequest = null;
            }
        });
        
        // let accerories_alert_msg = '';
        // if( add_to_cart_error ) {
        //     location.reload();
        // } else {
        //     accerories_alert_msg = alert_message_success ? alert_message_success :'Sản phẩm đã được thêm thành công';
        // }
        // if( accerories_alert_msg ) {
        //     jQuery( '.electro-wc-message' ).html(accerories_alert_msg);
        // }
    },

    accessory_refresh_fragments: function ( response ){
        var this_page = window.location.toString();
        var fragments = response.fragments;
        var cart_hash = response.cart_hash;

        // Block fragments class
        if ( fragments ) {
            jQuery.each( fragments, function( key ) {
                jQuery( key ).addClass( 'updating' );
            });
        }

        // Replace fragments
        if ( fragments ) {
            jQuery.each( fragments, function( key, value ) {
                jQuery( key ).replaceWith( value );
            });
        }

        // Cart page elements
        jQuery( '.shop_table.cart' ).load( this_page + ' .shop_table.cart:eq(0) > *', function() {

            jQuery( '.shop_table.cart' ).stop( true ).css( 'opacity', '1' ).unblock();

            jQuery( document.body ).trigger( 'cart_page_refreshed' );
        });

        jQuery( '.cart_totals' ).load( this_page + ' .cart_totals:eq(0) > *', function() {
            jQuery( '.cart_totals' ).stop( true ).css( 'opacity', '1' ).unblock();
        });
    }
}