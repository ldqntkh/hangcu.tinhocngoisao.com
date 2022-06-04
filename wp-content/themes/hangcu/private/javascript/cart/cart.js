'use strict';
const $ = jQuery;
var cart = {
    init: function() {
        cart.quantityButton();
        cart.submitUpdateCart();
    },

    quantityButton: function() {
        $('.product-quantity .quantity button').on('click', function() {
            let sub = $(this).attr('data-sub') == 'true';
            let inputId = $(this).attr('data-id');
            let inputVal = $('#'+inputId).val();
            inputVal = parseInt(inputVal);

            let inputMin = $('#'+inputId).attr('min') || 0;
            inputMin = parseInt(inputMin);

            let inputMax = $('#'+inputId).attr('max') || 0;
            inputMax = parseInt(inputMax);

            if( sub && inputVal < 2 ) return false;
            if( !sub && inputVal == inputMax ) return false;

            if( sub ) {
                inputVal--;
            } else {
                inputVal++;
            }
            $('#'+inputId).val(inputVal).trigger('change');
        })
    },

    submitUpdateCart: function() {
        var woocommerce_form = $( '.woocommerce-cart form' );
        woocommerce_form.off('change').on('change', '.qty', function(){
            let inputVal = $(this).val();
            inputVal = parseInt(inputVal);
            if( inputVal == 0 ) {
                inputVal = 1;
                $(this).val(inputVal);
            }

            let inputMin = $(this).attr('min') || 0;
            inputMin = parseInt(inputMin);

            let inputMax = $(this).attr('max') || 0;
            inputMax = parseInt(inputMax);

            if( inputVal < inputMin ) {
                inputVal = inputMin;
            } else if ( inputVal > inputMax ) {
                inputVal = inputMax;
            }
            $(this).val(inputVal);

            let form = $(this).closest('form');

            // emulates button Update cart click
            $("<input type='hidden' name='update_cart' id='update_cart' value='1'>").appendTo(form);

            // get the form data before disable button...
            let formData = form.serialize();

            // disable update cart and proceed to checkout buttons before send ajax request
            $("input[name='update_cart']").val('Updating…').prop('disabled', true);

            $('body').addClass('hangcu_loading');
            // update cart via ajax
            $.post( form.attr('action'), formData, function(resp) {
                // get updated data on response cart
                var shop_table = $('.cart-items', resp).html();
                var cart_totals = $('.cart-collaterals .cart_totals', resp).html();

                // replace current data by updated data
                $('.woocommerce-cart .cart-items')
                    .html(shop_table)
                $('.woocommerce-cart .cart-collaterals .cart_totals').html(cart_totals);
                $('body').removeClass('hangcu_loading');
                $('.process-checkout span.amount').html( $('.cart-subtotal span.amount').html() );
                cart.quantityButton();
                cart.submitUpdateCart();

                window.fragment_refresh = $.ajax({
                    type: "post",
                    url: '/wp-admin/admin-ajax.php',
                    data: {
                        action: "check_total_cart"
                    },
                    beforeSend: function () {
                        if( window.has_fragment_refresh ) {
                            window.has_fragment_refresh.abort()
                        }
                    },
                    success: function (response) {
                        if ( response.data && response.data.fragments ) {
                            let data = response.data;
                            $.each( data.fragments, function( key, value ) {
                                $( key ).replaceWith( value );
                            });
                
                            if ( window.sessionStorage ) {
                                sessionStorage.setItem( fragment_name, JSON.stringify( data.fragments ) );
                                localStorage.setItem( cart_hash_key, data.cart_hash );
                                sessionStorage.setItem( cart_hash_key, data.cart_hash );
                                // set_cart_hash( data.cart_hash );
                
                                if ( data.cart_hash ) {
                                sessionStorage.setItem( 'wc_cart_created', ( new Date() ).getTime() );
                                }
                            }
                
                            $( document.body ).trigger( '' );
                        }
                    },
                    error: function (response, errorStatus, errorMsg) {
                        
                    },
                    complete: function() {
                        window.has_fragment_refresh = null;
                    }
                });
            });
        }).off('click').on('click','.quantity input.minus', function() {
            var current = $(this).next('.qty').val();
            current--;
            $(this).next('.qty').val(current).trigger('change');
        }).off('click').on('click','.quantity input.plus', function() {
            var current = $(this).prev('.qty').val();
            current++;
            $(this).prev('.qty').val(current).trigger('change');
        })

        $( '.woocommerce-cart' ).on( 'click', "a.checkout-button.wc-forward.disabled", function(e) {
            e.preventDefault();
        }); 
    }
}

module.exports = cart;