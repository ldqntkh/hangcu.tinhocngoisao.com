<?php 

/*
    Template Name: List addresses of showroom
*/
    get_header();

    // render google api key, list addresses from BM
    $google_map_key = get_option( 'custom_preferences_options' )['google_map_key'];
    $list_address_store = get_option( 'custom_preferences_options' )['list_address_store'];
    $index = 0;
?>
    <div class="list_content_showrooms">
        <h1>Hệ thống showroom</h1>
        <div class="list-showrooms">
            <?php 
                $list_address_store_decode = json_decode( $list_address_store, true );
                foreach($list_address_store_decode as $item) :
                    $googleMapURL = 'https://maps.google.com/?q=' . $item['address'];
            ?>
                <p class="showroom-item <?php if ($item["default"]) echo "default" ?>">
                    <span>Tên cửa hàng: <strong><?php echo $item["store_name"]; ?></strong></span>
                    <span>Địa chỉ: <strong><?php echo $item["address"]; ?></strong></span>
                    <span>Hot line: <a href="tel:<?php echo $item["phone"]; ?>"><strong><?php echo $item["phone"]; ?></strong></a></span>
                    <span>Xem bản đồ: <strong class="show-map" data="<?php echo $index++; ?>" data-googlemap-url="<?php echo $googleMapURL; ?>">CLICK</strong></span>
                </p>
            <?php
                endforeach;
            ?>
        </div>
        <?php if (!empty( $google_map_key) ) : ?>
            <div id="list_address_store"></div>
        <?php endif; ?>
    </div>
    <script>
        var google_map_key = "<?php echo $google_map_key; ?>";
        var list_address_store = <?php echo $list_address_store; ?>;
    </script>
    <?php if (!empty( $google_map_key) ) : ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_map_key; ?>&libraries=geometry"></script>
    <?php endif; ?>
<?php

    get_footer();

?>