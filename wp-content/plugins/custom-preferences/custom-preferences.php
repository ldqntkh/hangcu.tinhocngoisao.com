<?php
    /**
     * Plugin Name:       Custom Preferences
     * Description:       Custom Preferences contais some configure information
     * Version:           1.0.0
     * Author:            Phat Le
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }

    define( 'CUSTOM_PREFERECE_VALUE_ID', 
        array(
            "custom_preferences_global" => "Global",
            "custom_preferences_search" => "Search keywords",
            'custom_preferences_zalo' => "Zalo",
            'custom_preferences_facebook' => 'Facebook',
            'custom_preferences_cache' => 'Cache',
            'custom_preferences_webhook' => 'Webhook',
            'custom_preferences_installment' => 'Trả góp'
        )
    );

    define( 'CUSTOM_PREFERECE_DIR', plugin_dir_path( __FILE__ ) );

    include CUSTOM_PREFERECE_DIR . '/global/custom-preference-global.php';
    include CUSTOM_PREFERECE_DIR . '/search/custom-search-hot-keys.php';
    include CUSTOM_PREFERECE_DIR . '/zalo/custom-preference-zalo.php';
    include CUSTOM_PREFERECE_DIR . '/facebook/custom-preference-facebook.php';
    include CUSTOM_PREFERECE_DIR . '/cache/custom-preference-cache.php';
    include CUSTOM_PREFERECE_DIR . '/installment/custom-preference-installment.php';
    include CUSTOM_PREFERECE_DIR . '/webhook/webhook.php';
    include CUSTOM_PREFERECE_DIR . '/register-hook/order-hooks.php';

    add_action( 'admin_menu', 'custom_preferences_menu' );

    function custom_preferences_menu() {
        add_options_page( 'Custom Preferences', 'Custom Preferences', 'manage_options', 'custom-preferences', 'custom_preferences_options' );
    }

    function custom_preferences_options() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        render_select_box_tab();

        // render global preferece
        echo '<div id="custom_preferences_global" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post">';
        settings_fields( 'custom_preferences_options' );
        do_settings_sections( 'custom_preferences' );
        echo '<input class="button" name="Submit" type="submit" value="' . __( 'Save Changes' ) . '" />';
        echo '</form></div>';

        // render search preferece
        echo '<div id="custom_preferences_search" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post" id="form-searchkey">';
        settings_fields( 'custom_preferences_search_options' );
        do_settings_sections( 'custom_preferences_search' );
        echo '<input class="button" name="Submit" type="submit" value="' . __( 'Save Changes' ) . '" />';
        echo '</form></div>';

        // init zalo
        echo '<div id="custom_preferences_zalo" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post">';
        settings_fields( 'custom_preferences_zalo_options' );
        do_settings_sections( 'custom_preferences_zalo' );
        echo '<input class="button" name="Submit" type="submit" value="' . __( 'Save Changes' ) . '" />';
        echo '</form></div>';

        // init facebook
        echo '<div id="custom_preferences_facebook" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post">';
        settings_fields( 'custom_preferences_facebook_options' );
        do_settings_sections( 'custom_preferences_facebook' );
        echo '<input class="button" name="Submit" type="submit" value="' . __( 'Save Changes' ) . '" />';
        echo '</form></div>';

         // init cache
         echo '<div id="custom_preferences_cache" class="custom_preferences_tab wrap" style="display:none">';
         //echo '<form action="options.php" method="post">';
         settings_fields( 'custom_preferences_cache_options' );
         do_settings_sections( 'custom_preferences_cache' );
         //echo '<input name="Submit" type="submit" value="' . __( 'Save Changes' ) . '" />';
         //echo '</form>';
         echo '</div>';

         // init installment
        echo '<div id="custom_preferences_installment" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post">';
        settings_fields( 'custom_preferences_installment_options' );
        do_settings_sections( 'custom_preferences_installment' );
        echo '<input class="button" name="Submit" type="submit" value="' . __( 'Save Changes' ) . '" />';
        echo '</form></div>';

        // webhook
        echo '<div id="custom_preferences_webhook" class="custom_preferences_tab wrap" style="display:none">';
        echo '<form action="options.php" method="post">';
        settings_fields( 'custom_preferences_webhook_options' );
        do_settings_sections( 'custom_preferences_webhook' );
        echo '<input class="button" name="Submit" type="submit" value="' . __( 'Save Changes' ) . '" />';
        echo '</form></div>';
    }

    

    function render_select_box_tab() {
    ?>
        <p>
            <label for="custom_preference_id">Chọn nhóm cấu hình:</label>
            <select id="custom_preference_id">
                <?php 
                    foreach( CUSTOM_PREFERECE_VALUE_ID as $key=>$value)
                    {
                        echo '<option value="' . $key . '">' .$value. '</option>';
                    }
                ?>
            </select>
        </p>
        <!-- wait on -->
        <script>
            const CUSTOM_PREFERENCE_TAB = 'CUSTOM_PREFERENCE_TAB';
            jQuery(document).on('change', '#custom_preference_id', function(e) {
                jQuery('.custom_preferences_tab').attr('style',  'display:none');
                jQuery('#' + e.target.value).removeAttr('style');
                sessionStorage.setItem(CUSTOM_PREFERENCE_TAB, e.target.value);
            });
            
            jQuery( document ).ready(function() {
                var item = sessionStorage.getItem(CUSTOM_PREFERENCE_TAB);
                if (item && item !== "") {
                    jQuery('#custom_preference_id').val(item);
                }
                jQuery('#custom_preference_id').trigger('change');
            });
        </script>
<?php
    }

    // register api route
    add_action( 'rest_api_init', function () {
        register_rest_route( 'rest_api/v1', '/create_product_cache_data', array(
            'methods' => 'GET',
            'callback' => 'create_product_cache_data',
        ) );
    } );

    function create_product_cache_data( WP_REST_Request $request ) {
        $post_number = 100;
        $start_page = $_GET[ 'start_page' ] ? absint( $_GET[ 'start_page' ] ) : 1;
        $order = 'DESC';
        $get_slug = 1;
        $perpage = ($start_page - 1) * $post_number;

        $query_args = array(
            'posts_per_page' => $post_number,
            'offset'         => $perpage,
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'no_found_rows'  => 1,
            'order'          => $order
        );
        $arrResult = [];
        $online_shop_featured_query = new WP_Query( $query_args );
        if ($online_shop_featured_query->have_posts()) :
            $products = array();
            while ( $online_shop_featured_query->have_posts() ) : $online_shop_featured_query->the_post();
                // Do Stuff
                global $product;
                if ($product->get_type() === 'variable') {
                    $regular_price = $product->get_variation_regular_price();
                    $sale_price = $product->get_variation_sale_price();
                } else {
                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();
                }
                $arrPt = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'link' => get_permalink( $product->get_id()),
                    'regular_price' => number_format((float)$regular_price, 0, '.', ','),
                    'sale_price' => number_format((float)$sale_price, 0, '.', ','),
                );
                
                $period = get_post_meta( $product->get_id(), 'warranty_period', true );
                // if (empty($period)) {
                //     $period = 36;
                // }
                $arrPt['period'] = $period;
                if ($get_slug == 1) {
                    $terms = get_the_terms( $product->get_id(), 'product_cat' );
                    $slugs = [];
                    if (count($terms) > 0) {
                        foreach($terms as $item) {
                            array_push($slugs, $item->slug);
                        }
                    }
                    $arrPt['slugs'] = $slugs;
                }

                array_push($arrResult, $arrPt);
            endwhile;
        endif;
        wp_reset_postdata();
        wp_send_json_success([
            "data_product"=> $arrResult
        ]);
    }