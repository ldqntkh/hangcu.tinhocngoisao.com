<?php 

/*
    Template Name: Share build pc
*/
    get_header();

    //var_dump($_GET['building_data']);
    if (isset($_GET['building_data'])) {
        $product_data_buildpc = json_decode(urldecode(base64_decode($_GET['building_data'])));

        if ($product_data_buildpc == null) wp_redirect(home_url());
        else {
            
            include_once( 'share-buildpc/render-list-products-buildpc.php' );
        }
    }
    else wp_redirect(home_url());
    

    get_footer();

?>