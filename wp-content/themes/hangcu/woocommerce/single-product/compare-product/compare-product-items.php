<?php 
defined( 'ABSPATH' ) || exit;
global $product;
global $product_type_id;

include_once WP_PLUGIN_DIR. '/hangcu-compare-products/product_compare/api/productType.php';
$productIds = ProductTypeApi::getListProductsMappingByProductIdAndProductType($product->get_id(), $product_type_id);

if( !function_exists('displayProductCompareElectroChild') ) {
    function displayProductCompareElectroChild($_product) { 
        global $product;
        ?>
        <div class="col-6 col-lg-3 col-xl-3 hangcu-product-item-compare <?php if( $_product->get_id() == $product->get_id()) echo 'current-product' ?> products ">
            <a href="<?php echo get_permalink( $_product->get_id()) ?>" <?php if ($_product->get_id() != $product->get_id()) echo ' target="_blank" ' ?>>
                <div class="right-prod">
                    <div class="img-prod">
                        <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $_product->get_id() ), 'medium', true )[0] ?>">
                    </div>
                    <?php 
                        if( $_product->get_id() == $product->get_id()) {
                            echo '<div class="title-prod-current"><i>'. __('Bạn đang xem:', 'hangcu') .'</i></div>';
                        } else {
                            echo '<div class="title-prod-current"><i>'. __('', 'hangcu') .'</i></div>';
                        }
                    ?>
                    <div class="title-prod"><?php echo $_product->get_name() ?></div>
                    <div class="price-prod"><?php  echo wc_price($_product->get_price()) ?></div>
                    <?php 
                        if( $_product->get_id() != $product->get_id()) {
                            $url = home_url('sssp/');
                            $url .= $product->get_slug();
                            $url .= '-vs-' . $_product->get_slug();
    
                            echo '<div class="title-prod-compare"><a href="'.$url.'" target="_blank">'.__('So sánh chi tiết', 'hangcu').'</a></div>';
                        }
                    ?>
                </div>
            </a>
        </div>
    <?php }
}


if ($productIds && count($productIds) > 0) { ?>

    <div class="compare-products" id="compare-products">
        <div class="col-12">
            <div class="row">
                <h4 class="title-compare"><?php echo __('So sánh với các sản phẩm tương tự', 'hangcu') ?></h4>
                <div class="col-12 cp-pd-items">
                    <div class="hangcu-product-show row">
                    <?php 
                        // display current product
                        displayProductCompareElectroChild($product);
                        $max_compare = 0;
                        foreach ($productIds as $key=>$item) {
                            $product_ = wc_get_product($item);
                            if ($product_) {
                                displayProductCompareElectroChild($product_);
                                if (++$max_compare == 3 ) break;
                            }
                        }
                    ?>
                    </div>
                </div>
                <div class="search-product-compare">
                    <div class="input-group">
                        <input type="text" class="form-control" name="input-product-compare" id="input-product-compare"
                            placeholder="<?php echo __('Nhập tên sản phẩm muốn so sánh', 'hangcu') ?>" 
                            aria-label="<?php echo __('Nhập tên sản phẩm muốn so sánh', 'hangcu') ?>" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                    <div id="search-suggestions">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
       const product_id_compare = '<?php echo $product->get_id() ?>';
        const product_slug_compare = '<?php echo $product->get_slug() ?>';
        const product_type_id = '<?php echo $product_type_id ?>';
    </script>
<?php }
