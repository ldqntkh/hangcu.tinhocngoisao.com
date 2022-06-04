module.exports = {
    init: function() {
        /* global wc_add_to_cart_params */
        jQuery( function( $ ) {

            /**
             * SimpleAddToCartHandler class.
             */
            var SimpleAddToCartHandler = function() {
                this.requests   = [];
                this.addRequest = this.addRequest.bind( this );
                this.run        = this.run.bind( this );
                $('#simple-product-add-to-cart').off('click');
                
                $( document.body )
                    .on( 'click', '#simple-product-add-to-cart', { addToCartHandler: this }, this.onAddToCart )
            };

            /**
             * Add add to cart event.
             */
            SimpleAddToCartHandler.prototype.addRequest = function( request ) {
                this.requests.push( request );

                if ( 1 === this.requests.length ) {
                    this.run();
                }
            };

            /**
             * Run add to cart events.
             */
            SimpleAddToCartHandler.prototype.run = function() {
                var requestManager = this,
                    originalCallback = requestManager.requests[0].complete;

                requestManager.requests[0].complete = function() {
                    if ( typeof originalCallback === 'function' ) {
                        originalCallback();
                    }

                    requestManager.requests.shift();

                    if ( requestManager.requests.length > 0 ) {
                        requestManager.run();
                    }
                };

                $.ajax( this.requests[0] );
            };

            /**
             * Handle the add to cart event.
             */
            SimpleAddToCartHandler.prototype.onAddToCart = function( e ) {
                var $thisbutton = $( this );

                e.preventDefault();
                if( window.has_fragment_refresh ) {
                    window.has_fragment_refresh.abort()
                }
                $('body').addClass( 'hangcu_loading' );

                var data = {};

                data['product_id'] = $thisbutton.val();
                data['quantity'] = 1;

                // Trigger event.
                // $( document.body ).trigger( 'adding_to_cart', [ $thisbutton, data ] );

                e.data.addToCartHandler.addRequest({
                    type: 'POST',
                    url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'add_to_cart' ),
                    data: data,
                    success: function( response ) {
                        if ( ! response ) {
                            return;
                        }

                        if ( response.error && response.product_url ) {
                            // console.log(response)
                            // window.location = response.product_url;
                            $('body').append(`
                                <div id="tooltip-addcart-error">${response.product_url}</di>
                            `);
                            setTimeout(()=> {
                                $('#tooltip-addcart-error').remove();
                            }, 5000);
                            $('body').removeClass( 'hangcu_loading' );
                            return;
                        }

                        // Redirect to cart option
                        if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
                            window.location = wc_add_to_cart_params.cart_url;
                            return;
                        }

                        // Trigger event so themes can refresh other areas.
                        // $( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, null ] );
                        // reset total item
                        if( response.fragments['span.cart-items-count-number'] ) {
                            $('span.cart-items-count').text(response.fragments['span.cart-items-count-number']);
                            
                            let skeys = Object.keys(sessionStorage);
                            
                            for( let i = 0; i < skeys.length; i++ ) {
                                if( skeys[i].indexOf('wc_fragments_') == 0 ) {
                                    sessionStorage.setItem( skeys[i], JSON.stringify( response.fragments ) );
                                    break;
                                }
                            }
                            

                            if( $('#mb-product-overlay') && $('#mb-product-overlay').length > 0 ) {
                                $('#mb-product-overlay').addClass('active');
                                $('#mb-product-detail-bottom').addClass('active');
                            } else {
                                $('#tooltip-minicart').css({
                                    display: 'block'
                                });
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            }
                        }
                        // trigger show popup
                        $('body').removeClass( 'hangcu_loading' );
                    },
                    dataType: 'json'
                });
            };

            /**
             * Init SimpleAddToCartHandler.
             */
            new SimpleAddToCartHandler();
        });
    }
}
