<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) );?>">
    <?php 
        $price =  $product->get_price_html(); 
        if (strpos($price, 'del')) {
			$price = str_replace( '<del>', '<del><span class="price-label">Giá niêm yết: </span>', $price );
	
			$percent = floor(intval($product->get_sale_price())/intval($product->get_regular_price())*100);
	
			$price = str_replace( '</del>', '<strong>(-'. strval(100 - $percent) .'%)</strong></del>', $price );
			echo $price;
		} else  {
			echo $price;
		}
    ?>
    <?php 
        do_action('hangcu_after_display_price');
    ?>
</p>
