
<?php 

    $product_types = [
        "main" =>  __('Main', 'woocommerce-buildpc'),
        "cpu" => __('CPU', 'woocommerce-buildpc'),
        "ram" => __('RAM', 'woocommerce-buildpc'),
        "ssd" => __('SSD', 'woocommerce-buildpc'),
        "hdd" => __('HDD', 'woocommerce-buildpc'),
        "optane" => __('Optane', 'woocommerce-buildpc'),
        "vga" => __('VGA', 'woocommerce-buildpc'),
        "power" => __('Power', 'woocommerce-buildpc'),
        "case" => __('Case', 'woocommerce-buildpc'),
        "radiator" => __('Radiator', 'woocommerce-buildpc'),
        "screen" => __('Screen', 'woocommerce-buildpc'),
        "keyboard" =>  __('Keyboard', 'woocommerce-buildpc'),
        "mouse" => __('Mouse', 'woocommerce-buildpc'),
        "headphone" => __('Headphone', 'woocommerce-buildpc'),
        "soundcase" => __('Soundcase', 'woocommerce-buildpc')
    ];

?>
<div class="wrapper">
    <div class="build-pc-function">
        <div class="build-pc-body">
        <?php 
            $index = 0;
            $total_price = 0;
            foreach($product_data_buildpc as $key=>$item){
                $index++;
            ?>
                <div class="product-type-item">
                    <div class="left-content">
                        <?php echo $index . ' . ' . $product_types[$key];?> 
                    </div>
                    <div class="right-content">
                        <?php if ($item->product_id !== null) :
                            $product = wc_get_product(intval($item->product_id));
                            if ( empty( $product ) ) {
                                continue;
                            }
                        
                            if ( $product->get_type() === 'variable' ) {
                                $regularPrice = $product->get_variation_regular_price();
                                $salePrice = $product->get_variation_sale_price();
                            } else {
                                $regularPrice = $product->get_regular_price();
                                $salePrice = $product->get_sale_price();
                            }
                            $price = ($salePrice !== '' && $salePrice !== 0 && $salePrice < $regularPrice) ? $salePrice : $regularPrice;
                            
                            $total_price += $price * $item->quantity;
                            $name = $product->get_name();
                            $link = $product->get_permalink();
                            $imageLink = wp_get_attachment_url($product->get_image_id());
                            $rating = $product->average_rating;   
                        ?>
                            <div class="choose-product-item-detail">
                                <div class="image">
                                    <img src="<?php echo $imageLink; ?>">
                                </div>
                                <div class="content">
                                    <a href="<?php echo $link; ?>" target="_blank">
                                        <p class="name"> <?php echo $name; ?> </p>
                                        <p class="price"> Giá: <?php echo wc_price($price); ?></p>
                                        <p class="productid"> Mã sản phẩm: <?php echo $item->product_id; ?> </p>
                                        <div class="star-rating" style="margin: 0">
                                            <span style="width: <?php echo ($rating / 5 * 100) ?>%;">Rated <strong class="rating"><?php echo $rating; ?></strong> out of 5</span>
                                        </div>
                                    </a>
                                    <div class="action">
                                        <p class="input-group">
                                            <span>Số lượng : </span>
                                            <input name="main_quantity" type="number" min="1" max="10" value="<?php echo $item->quantity; ?>" readonly=true><span>= <strong class="price"><?php echo wc_price($price * $item->quantity) ?></strong> </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
        <?php } ?>
            <div class="total-price">
                <span>Giá tiền dự kiến: <strong><?php echo wc_price($total_price) ?><strong></span>
            </div>
        </div>

        <div class="build-pc-footer">
            <!-- <div class="btn-item">
                <button type="button" class="btn btn-saveconfig">
                    <i class="fa fa-floppy-o"></i>Lưu cấu hình
                </button>
            </div> -->
            <div class="btn-item">
                <button type="button" class="btn btn-saveimg">
                    <a href="<?php echo home_url( '/build-pc/' ) . '?building_data='.$_GET['building_data'] ?>">
                        <i class="fa fa-edit"></i>Chỉnh sửa cấu hình
                    </a>
                </button>
            </div>
            <!-- <div class="btn-item">
                <button type="button" class="btn btn-share">
                    <i class="fa fa-facebook"></i>Chia sẻ cấu hình
                </button>
            </div> -->
            <div class="btn-item">
                <button type="button" class="btn btn-add-to-cart">
                    <i class="fa fa-shopping-cart"></i>Thêm vào giỏ hàng
                </button>
            </div>
        </div>
    </div>
</div>