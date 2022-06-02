<?php
/**
 * Plugin Name:       WooCommerce Grouped Product
 * Description:       Add quantity for single product in grouped product
 * Version:           1.0.0
 * Author:            Anthony Le
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'CUSTOM_GROUPED_PRODUCT_DIR', plugin_dir_path( __FILE__ ) );

class WC_Meta_box_grouped_product {
    public function init() {
        include CUSTOM_GROUPED_PRODUCT_DIR . 'views/grouped-product.php';
    }

    function custom_quantity_field() {
        $args = array(
        'id' => 'custom_product_quantity',
        'label' => __( 'Quantity', 'woocommerce' ),
        'class' => 'cfwc-custom-field',
        'desc_tip' => true,
        'description' => __( 'Nhập số lượng sản phẩm.', 'woocommerce' ),
        'type' => 'number',
        'value' => '1',
        'custom_attributes' => array(
            'min'	=> '1'
        )
        );
        woocommerce_wp_text_input( $args );
    }

    function save_grouped_product_data( $post_id ) {
        $product = wc_get_product( $post_id );
        $title = isset( $_POST['group_product_data'] ) ? $_POST['group_product_data'] : '';
        $product->update_meta_data( 'group_product_data', $title );
        $product->save();
    }

    function display_grouped_product_data() {
        global $post;
        $product = wc_get_product( $post->ID );
        $products = $product->get_meta( 'group_product_data' );
        $productsData = $products ? str_replace("\\", "", $products) : '';
        echo "<input id='group_product_data' name='group_product_data' type='hidden' value='" . $productsData . "' />";
    }

    // use this action for showing grouped product
    function woocommerce_custom_grouped_add_to_cart() {
        global $product;

        $products = $product->get_meta( 'group_product_data' );

        if ( $products ) {
            wc_get_template( 'single-product/add-to-cart/grouped.php', array(
                'grouped_products_data'   => $products,
                'quantites_required' => false,
            ) );
        }
    }

    // register css
    function register_custom_grouped_product_css() {
        wp_register_style( 'custom_grouped_product_style', plugins_url('woocommerce-grouped-product/assets/css/admin-custom-grouped-product.css', false, '1.0.0', __FILE__ ));
        wp_enqueue_style( 'custom_grouped_product_style' );
    }

    function register_custom_grouped_product_script() {
        wp_register_script( 'custom_grouped_product_script', plugins_url('woocommerce-grouped-product/assets/js/admin-custom-grouped-product.js'), '', '', true );
        wp_enqueue_script( 'custom_grouped_product_script' );
    }
}
add_action( 'woocommerce_product_options_related' , array(new WC_Meta_box_grouped_product(), 'init') );
add_action( 'woocommerce_process_product_meta', array(new WC_Meta_box_grouped_product(), 'save_grouped_product_data') );
add_action( 'woocommerce_product_options_related', array(new WC_Meta_box_grouped_product(), 'display_grouped_product_data') );
remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
add_action( 'woocommerce_grouped_add_to_cart', array(new WC_Meta_box_grouped_product(), 'woocommerce_custom_grouped_add_to_cart'), 30 );
add_action( 'admin_enqueue_scripts', array(new WC_Meta_box_grouped_product(), 'register_custom_grouped_product_css') );
add_action( 'admin_enqueue_scripts', array(new WC_Meta_box_grouped_product(), 'register_custom_grouped_product_script') );
?>