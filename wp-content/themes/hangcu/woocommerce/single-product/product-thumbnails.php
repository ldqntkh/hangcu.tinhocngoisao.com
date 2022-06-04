<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$attachment_ids = $product->get_gallery_image_ids();
$arrFullSize = array();

if ( $attachment_ids && $product->get_image_id() ) {
    $arrThums = array();
	foreach ( $attachment_ids as $attachment_id ) {
        $image_large = wp_get_attachment_image_src( $attachment_id, 'full' )[0];
        $image_small = wp_get_attachment_image_src( $attachment_id, 'medium' )[0];
        if ( !empty($image_large) && !empty($image_small) ) {
            array_push( $arrFullSize, $image_large );
            array_push( $arrThums, $image_small );
        }
    } 
    
    ?>
        
<?php } ?>

<div class="product-thumbs-slide">
    <ul>
        <?php 
            if ($arrFullSize && count($arrFullSize) > 0) :
        ?>
        <li class="image-thumbs text-center" id="image-thumbs-slide">
            <div class="wrapper-image">
                <img src="<?php echo $arrThums[0] ?>" alt="" />
            </div>
            <span class="text-description"><?php echo count($arrFullSize) . ' ' . __('ảnh', 'hangcu'); ?></span>
        </li>
        <?php endif; ?>
        <?php 
            do_action( 'product-thumbs-content' );
        ?>
    </ul>
</div>
<?php 
    if ($arrFullSize && count($arrFullSize) > 0) :
?>
<div class="popup-images-thumbs d-none" id="popup-images-thumbs">
    <div id="content-img-thumbs"></div>
    <button id="popup-images-thumbs-close" class="navbar-toggler pull-right flip close-content" type="button">
        <i class="ec ec-close-remove"></i>
    </button>
    <div id="content-img-thumbs-rect"></div>
</div>
<script>
    const arrFullSize = <?php echo json_encode($arrFullSize) ?>;
    const arrThums = <?php echo json_encode($arrThums) ?>;
</script>
<?php endif; ?>
