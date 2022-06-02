<?php

class ProductManager {
    public function getDiscountTimeRemaining($product_id) {
        $_sale_price_dates_to = get_post_meta( $product_id, '_sale_price_dates_to', true );

        if ($_sale_price_dates_to === "") {
            return null;
        }
        return date("Y-m-d", $_sale_price_dates_to);
    }
}

?>