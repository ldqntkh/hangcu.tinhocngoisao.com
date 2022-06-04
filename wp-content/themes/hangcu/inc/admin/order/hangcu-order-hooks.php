<?php
add_action('woocommerce_order_actions_end', 'hangcu_add_btn_copy_order_admin');
add_action( 'wp_ajax_copy_order_for_admin', 'hangcu_copy_order_for_admin' );
add_filter( 'wc_order_is_editable', 'hangcu_allow_edit_order', 10, 2 );
add_action( 'save_post', 'hangcu_update_allow_edit_order');
add_action( 'manage_shop_order_posts_custom_column', 'hc_admin_show_content_table_order' );
add_filter( 'manage_edit-shop_order_columns', 'hc_admin_add_column_order_table_header', 20 );
add_action( 'wp_ajax_ajax_sync_order_banhang_id_func', 'ajax_sync_order_banhang_id_func' );