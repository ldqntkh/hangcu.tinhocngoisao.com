const $ = jQuery;

const Share = {
    init: function () {
      // this.customizePrice();
      this.initScrollPage();
      // this.getviewedproduct();
      // this.getChatOption();
      this.initCarouselHeader();
      this.initHomePageWidgetProduct();
      this.initMobileFooterClick();
      this.initDesktopShowMenu();
      this.initSubmitFormNewsletter();
      

      $(document).on('click', '.guaven_woos_showallli a', function(e) {
        e.preventDefault();
        $('form.navbar-search').submit();
        return false;
      } );

      $( '.header-icon__cart a' ).off( 'click' );
      $('#tooltip-minicart .electro-close-icon').on( 'click', function() {
        $('#tooltip-minicart').css({
          display: 'none'
        })
      });

      setTimeout(function() {
        $('.woocommerce-notices-wrapper').css({
            opacity: 0,
            display: 'none'
        })
      }, 10000);

      if( $('body').hasClass('body-mobile') ) {
        $('#search').focus( function() {
          $('body').css({
            overflow: 'hidden'
          });
        });
        
        $('#search').blur( function() {
          $('body').css({
            overflow: 'initial'
          });
        });
      }

      $('form.navbar-search').on('submit',function(e) {
        if( $('input#search').val().trim() == '' ) {
          $('input#search').val($('input#search').val().trim());
          e.preventDefault();
          return false;
        }
        let form = e;
        form.preventDefault();
        setTimeout(() => {
          if( $('#woo_search_ids').val().trim() == '' ) {
            return false;
          } else {
            
            let form = document.createElement('form');
            form.setAttribute('method', 'post');
            form.setAttribute('action', $('form.navbar-search').attr('action'));

            let search = document.createElement('input');
            search.setAttribute('type', 'hidden');
            search.setAttribute('name', 's');
            search.setAttribute('value', $('#search').val());
            form.appendChild(search);

            let woo_search_ids = document.createElement('input');
            woo_search_ids.setAttribute('type', 'hidden');
            woo_search_ids.setAttribute('name', 'woo_search_ids');
            woo_search_ids.setAttribute('value', $('#woo_search_ids').val());
            form.appendChild(woo_search_ids);

            document.body.appendChild(form);
            form.submit();
          }
        }, 100);
        
      });

    //   window.fragment_refresh = $.ajax({
    //     type: "get",
    //     url: '/wp-json/v1/check_cart_total',
    //     data: {
    //         "cart-ajax": true
    //     },
    //     cache: false ,
    //     beforeSend: function () {
    //         if( window.has_fragment_refresh ) {
    //             window.has_fragment_refresh.abort()
    //         }
    //     },
    //     success: function (response) {
    //         if ( response.data && response.data.fragments ) {
    //             let data = response.data;
    //             $.each( data.fragments, function( key, value ) {
    //                 $( key ).replaceWith( value );
    //             });
    
    //             if ( window.sessionStorage ) {
    //                 sessionStorage.setItem( fragment_name, JSON.stringify( data.fragments ) );
    //                 localStorage.setItem( cart_hash_key, data.cart_hash );
    //                 sessionStorage.setItem( cart_hash_key, data.cart_hash );
    //                 // set_cart_hash( data.cart_hash );
    
    //                 if ( data.cart_hash ) {
    //                 sessionStorage.setItem( 'wc_cart_created', ( new Date() ).getTime() );
    //                 }
    //             }
    
    //             $( document.body ).trigger( '' );
    //         } else {
    //             window.fragment_refresh = $.ajax({
    //                 type: "post",
    //                 url: '/wp-admin/admin-ajax.php',
    //                 data: {
    //                     action: "check_total_cart"
    //                 },
    //                 beforeSend: function () {
    //                     if( window.has_fragment_refresh ) {
    //                         window.has_fragment_refresh.abort()
    //                     }
    //                 },
    //                 success: function (response) {
    //                     if ( response.data && response.data.fragments ) {
    //                         let data = response.data;
    //                         $.each( data.fragments, function( key, value ) {
    //                             $( key ).replaceWith( value );
    //                         });
                
    //                         if ( window.sessionStorage ) {
    //                             sessionStorage.setItem( fragment_name, JSON.stringify( data.fragments ) );
    //                             localStorage.setItem( cart_hash_key, data.cart_hash );
    //                             sessionStorage.setItem( cart_hash_key, data.cart_hash );
    //                             // set_cart_hash( data.cart_hash );
                
    //                             if ( data.cart_hash ) {
    //                             sessionStorage.setItem( 'wc_cart_created', ( new Date() ).getTime() );
    //                             }
    //                         }
                
    //                         $( document.body ).trigger( '' );
    //                     }
    //                 },
    //                 error: function (response, errorStatus, errorMsg) {
                        
    //                 },
    //                 complete: function() {
    //                     window.has_fragment_refresh = null;
    //                 }
    //             });
    //         }
    //     },
    //     error: function (response, errorStatus, errorMsg) {
            
    //     },
    //     complete: function() {
    //         window.has_fragment_refresh = null;
    //     }
    // });

      window.fragment_refresh = {
        url: '/?wc-ajax=get_refreshed_fragments',
        type: 'POST',
        beforeSend: function () {
          if( window.has_fragment_refresh ) {
            window.has_fragment_refresh.abort()
          }
        },
        success: function( data ) {
            if ( data && data.fragments ) {
     
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
        complete: function() {
          window.has_fragment_refresh = null;
        }
      };
      
      // this.initCartTotal();
    },

    initCartTotal: function() {
      // lấy data từ store
      let skeys = Object.keys(sessionStorage);
      let flag = false;
      for( let i = 0; i < skeys.length; i++ ) {
        if( skeys[i].indexOf('wc_fragments_') == 0 ) {
          // lấy data
          let fragments = sessionStorage.getItem( fragment_name );
          if( fragments ) {
            fragments = JSON.parse(fragments);
            $.each( fragments, function( key, value ) {
              $( key ).replaceWith( value );
            });
          } else {
            window.has_fragment_refresh = jQuery.ajax(window.fragment_refresh);
          }
          flag = true;
          break;
        }
      }
      if( !flag ) {
        window.has_fragment_refresh = jQuery.ajax(window.fragment_refresh);
      }
    },

    initScrollPage: function () {
      if (location.hash) {
        this.scrollTo(location.hash);
      }
    },
    scrollTo: function (selector) {
      if (selector && jQuery(selector).length) {
        jQuery("html, body").stop().animate({ scrollTop: jQuery(selector).offset().top }, 700, 'swing');
      }
    },
  
    customizePrice: function() {
      if (typeof jQuery == 'function' && jQuery(".electro-price.customize-price").length > 0){
        jQuery(function(){
          let product_ids=[];
          jQuery('.electro-price.customize-price').each(function(i, obj) {
            if ( jQuery(obj).attr('data-product-id') !== 'undefined' )
              product_ids.push(jQuery(obj).attr('data-product-id'));
          });
    
          if ( product_ids.length === 0 ) return;
          //jQuery(".electro-price.customize-price").css({"filter": "blur(5px)", "pointer-events": "none"});
          
    
          jQuery.ajax({
            url: '/wp-json/rest_api/v1/getproductprice',
            method: 'POST',
            data: {
              product_ids
            },
            success: function (res) {
              let products = res.data;
              
              let product_ids = Object.keys( products );
              for( let i = 0; i < product_ids.length; i++ ) {
                let id = product_ids[i];
                let item = products[id];
                
                if ( item['_sale_price'] ) {
                  jQuery(jQuery('.productct-' + id).find('ins bdi')).html( item['_sale_price'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '<span class="woocommerce-Price-currencySymbol">₫</span>' );
                  jQuery(jQuery('.productct-' + id).find('del bdi')).html( item['_regular_price'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '<span class="woocommerce-Price-currencySymbol">₫</span>' );
                  jQuery(jQuery('.productct-' + id).find('del >strong')).html( (100 - (item['_sale_price']/item['_regular_price'])*100).toFixed(0) + '%' );
                } else {
                  jQuery(jQuery('.productct-' + id).find('bdi')).html( item['_price'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '<span class="woocommerce-Price-currencySymbol">₫</span>' );
                }
              }
            },
            error: function (err) {
              
            }, 
            complete: function() {
              //jQuery(".electro-price.customize-price").trigger('swift-performance-ajaxify-item-done');
              //jQuery(".electro-price.customize-price").css({"filter": "none", "pointer-events": "unset"});
            }
          });
        });
      }
    },
  
    getviewedproduct: function() {
      let element = jQuery("#getviewedproduct");
      if ( element && element.length > 0 ) {
        let productids = localStorage.getItem("productids");
        if ( !productids ) return;
        else {
            productids = JSON.parse(productids);
            if (productids.length === 0) return;
        }
        jQuery.ajax({
          url: '/wp-json/rest_api/v1/getviewedproduct',
          method: 'POST',
          data: {
            product_ids: productids
          },
          success: function (res) {
            if (res.data && res.data !== '') {
              element.html( res.data );
              var $owl = jQuery('#getviewedproduct .owl-carousel').owlCarousel(JSON.parse(jQuery('#getviewedproduct [data-carousel-options]').attr('data-carousel-options')));
  
              $owl.trigger('refresh.owl.carousel');
  
              // Owl Carousel
              jQuery( '#getviewedproduct .slider-next' ).on( 'click', function(e) {
                e.preventDefault();
                var owl = jQuery( jQuery( this ).data( 'target' ) + ' .owl-carousel' );
                owl.trigger( 'next.owl.carousel' );
                return false;
              });
  
              jQuery( '#getviewedproduct .slider-prev' ).on( 'click', function(e) {
                e.preventDefault();
                var owl = jQuery( jQuery( this ).data( 'target' ) + ' .owl-carousel' );
                owl.trigger( 'prev.owl.carousel' );
                return false;
              });
            }
            
          },
          error: function (err) {
            
          }, 
          complete: function() {
            // jQuery(document).trigger('triggerItemHeight');
            function resizeFrame() {
              setTimeout(function() {
                jQuery("#getviewedproduct .products .product").each(function () {
                  jQuery(this).css({
                    height: window.innerWidth >= 1024 ? window.getComputedStyle(this).height : 'initial'
                  });
                });
              }, 1000)
            }
            resizeFrame();
  
            jQuery(window).resize(function(){
              resizeFrame();
            });
          }
        });
      }
      
    },
  
    getChatOption: function() {
      setTimeout(function() {
        jQuery.ajax({
          url: '/wp-json/rest_api/v1/getchatoption',
          method: 'GET',
          success: function (res) {
            if (res.data && res.data !== '') {
              jQuery('body').append(res.data);
            }
          },
          error: function (err) {
            
          }, 
          complete: function() {
            
          }
        });    
      }, 2000);
    },
  
    initCarouselHeader: function() {
      if( jQuery('#hc-banner-header').length > 0 ) {
        let banner = jQuery('#hc-banner-header');
        // jQuery( '#page' ).css({
        //   "padding-top": "50px"
        // });
        banner.owlCarousel({
          autoplay: true,
          autoplayTimeout: 4000,
          stagePadding: 0,
          items: 1,
          loop:true,
          margin:0,
          singleItem:true,
          dots: false
        });
      }
    },

    initHomePageWidgetProduct: function() {
      if( jQuery('#widget_hangcu_list_product .lst-product-body.owl-carousel').length > 0 ) {
        let block = jQuery('#widget_hangcu_list_product .lst-product-body.owl-carousel');
          block.owlCarousel({
            autoplay: true,
            autoplayTimeout: 4000,
            stagePadding: 0,
            items: 2,
            loop:true,
            margin:0,
            // singleItem:true,
            lazyLoad: true,
            dots: false,
            nav: true,
            scrollPerPage: true,
            navText:["<i class='hc-left'></i>","<i class='hc-right'></i>"],
            responsive : {
              600 : {
                items: 3
              },
              768 : {
                items: 3
              },
              1024 : {
                items: 5
              },
              1200 : {
                items: 5
              }
            }
          });
      }
    },

    initMobileFooterClick: function() {
      $('.body-mobile footer .footer-widgets .content-block-widget').on('click', function() {
        if( $(this).hasClass('active') ) {
          $(this).removeClass('active')
        } else {
          $(this).addClass('active')
        }
      })
    },

    initDesktopShowMenu: function() {
      // $(".off-canvas-navbar-toggle-buttons .navbar-toggle-hamburger").off("click");
      $(".off-canvas-navbar-toggle-buttons .navbar-toggle-hamburger").off("click").on("click", function() {
        // b && (c.transform = "translateX(-250px)"),
        // $(this).parents(".stuck").length > 0 && $("html, body").animate({
        //     scrollTop: $("body")
        // }, 0),
        
        let top = 0;
        if( $('#hc-banner-header') ) {
          top += 60;
        }
        top += $(this).closest(".off-canvas-navbar-toggle-buttons").position().top + $('.header-logo-area').innerHeight();
        var doc = document.documentElement;
        var topBS = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
        top = top - topBS;
        $('#desktop-small-menu').css({
            top: top,
            left: 0,
            right: 0,
            height: 'auto',
            // transition: "all .5s",
            'z-index': 9999999
        })
        // $(this).closest(".off-canvas-navbar-toggle-buttons").toggleClass("toggled");
        $("#page").toggleClass("off-canvas-bg-opacity");
        $("body").toggleClass("off-canvas-active");
        setTimeout(function() {
          $('.off-canvas-bg-opacity>header, .off-canvas-bg-opacity>#content').off('click').on('click', function() {
              $('#desktop-small-menu').css({left: '-100%'});
              $('.off-canvas-bg-opacity>header, .off-canvas-bg-opacity>#content').off('click');
              $("#page").toggleClass('off-canvas-bg-opacity');
              $("body").toggleClass("off-canvas-active");
          });
      }, 500);
    });
    },

    initSubmitFormNewsletter: function() {
      $(document).on('submit','.newsletter-form form',function(e){
        e.preventDefault();
        let action = jQuery('.newsletter-form form').attr('action');

        jQuery('body').addClass('hangcu_loading');

        jQuery.ajax({
          url: action,
          method: 'POST',
          data: $('.newsletter-form form').serialize(),
          success: function (res) {
            jQuery('body').removeClass('hangcu_loading');

            // show popup
            jQuery('.newsletter-form form input.tnp-email').val('');
            let html = `<div id="popup-account" class="popup-newsletter">
                          <div class="form-account">
                              <span id="close-newsletter-popup" class="electro-close-icon"></span>
                              <div class="body-content">
                                <div class="form-content">
                                    <img src="/wp-content/themes/hangcu-electro-child-v1/assets/images/email-newsletter.jpg" alt="" />
                                    <div>
                                      <strong>Đăng kí nhận email thành công!</strong>
                                      <p>Cảm ơn bạn đã đăng kí nhận bản tin của GEARVN.</p>
                                    </div>
                                </div>
                              </div>
                          </div>
                        </div>`;

            jQuery('body').append(html);
          },
          error: function (err) {
            jQuery('body').removeClass('hangcu_loading');
          }
        });
      });

      jQuery(document).on('click', '#close-newsletter-popup', function() {
        jQuery('.popup-newsletter').remove()
      })
    }
  };
  
  module.exports = Share;