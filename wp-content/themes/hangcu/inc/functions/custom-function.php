<?php 

function hangcu_product_short_specifications() {
    global $product;
    $short_specifications = get_field('short_specifications');
    if ( isset( $short_specifications ) ) { ?>
        <div class="tableparameter">
            <h3><?php echo  __('Thông số kỹ thuật', 'hangcu');?></h3>
            <?php echo $short_specifications;?>
        </div>
    <?php } ?>

        <?php 
            $image_specifications = get_field('image_specifications');
        
        $specifications = get_field('specifications');
            if ( isset( $specifications ) ) { ?>
                <button type="button" class="viewparameterfull"><?php echo __('Xem cấu hình chi tiết', 'hangcu'); ?></button>
                <div class="viewparameterfullcontent">
                    <div class="content">
                        <h2><?php echo __('Thông số kỹ thuật chi tiết', 'hangcu') . ' ' . $product->get_name(); ?></h2>
                        <img src="<?php echo $image_specifications ?>"/>
                        <?php echo $specifications; ?>
                    </div>
                    <button class="navbar-toggler pull-right flip close-content" type="button">
                        <i class="ec ec-close-remove"></i>
                    </button>
                </div>
            <?php }
}

