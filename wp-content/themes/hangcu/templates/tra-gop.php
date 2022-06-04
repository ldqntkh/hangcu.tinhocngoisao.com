<?php 

/*
    Template Name: Thanh toán trả góp
*/

/**
 * hiện tại chỉ làm layout demo nên chỉ lấy theo product slug
 */

if ( isset( $wp->query_vars['product_id'] ) ) {
  function hangcu_installment_script() {
    $script_installment = get_stylesheet_directory_uri(). '/assets/javascript/react-installment.js';
    wp_enqueue_script('script_installment', $script_installment, array('jquery'), STYLE_VERSION, true);
  }
  add_action( 'wp_enqueue_scripts', 'hangcu_installment_script' );
  $product_id = $wp->query_vars['product_id'];
  $product_id = trim( $product_id );
  $product = wc_get_product( $product_id );

  $product_obj = array();

  if ( !isset( $product ) || empty( $product ) ) {
    header("Location:" . home_url());
    exit;
  }


  $stop_selling = get_field('stop_selling', $product->get_ID());
  $enable_tra_gop = get_field('cho_phep_tra_gop', $product->get_ID());
  $enable_tra_gop = false;
  if ( !$enable_tra_gop || $stop_selling ) {
    header("Location:" . home_url());
    exit;
  }

  if ( $enable_tra_gop && !$stop_selling ) {
    $dich_vu_tra_gop = get_field('dich_vu_tra_gop', $product->get_ID());
    if ( count($dich_vu_tra_gop) == 0 ) {
      header("Location:" . home_url());
      exit;
    }

    $product_obj['product_id'] = $product->get_ID();
    $product_obj['product_name'] = $product->get_name();
    $product_obj['product_slug'] = $product->get_slug();
    $product_obj['product_price'] = $product->get_price();
    $product_obj['html_price'] = wc_price($product->get_price());

    get_header();

    echo '<div id="product-installment-payment"></div>';

    ?>

    <script>
      const product_obj = <?php echo json_encode( $product_obj ) ?>;
    </script>

    <?php
  
    get_footer();
  } else {
    header("Location:" . home_url());
    exit;
  }

} else {
  header("Location:" . home_url());
  exit;
}


