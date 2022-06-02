<?php 

/*
    Template Name: List Product Sale Price
*/
    get_header();
    $list_product_sale_price = get_option( 'custom_preferences_options' )['list_product_sale_price'];
    $list_product_sale_price = json_decode( $list_product_sale_price, true );
?>

    <script>
        var list_product_sale_price = <?php echo json_encode($list_product_sale_price); ?>;
        
    </script>
    <div id="list_sale_price"></div>
<?php
    
    get_footer();

?>