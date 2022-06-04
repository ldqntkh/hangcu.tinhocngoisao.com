<?php 

add_action( 'product_cat_edit_form_fields', 'init_category_option_select_cats' );
add_action('edit_term','hangcu_custom_edit_taxonomy');
add_filter('manage_edit-product_cat_columns', 'add_post_tag_columns');
add_filter('manage_product_cat_custom_column', 'category_custom_column_value', 10, 3);