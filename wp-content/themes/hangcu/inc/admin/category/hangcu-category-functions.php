<?php 

if ( !function_exists('init_category_option_select_cats') ) {
    function init_category_option_select_cats($tag) { 
        $term_id = $tag->term_id;
        $meta_key_docquyengiatot = "hangcu_cat_docquyengiatot";
        $meta_key_docquyengiatot_title = "hangcu_cat_docquyengiatot_title";

        $meta_key_sanphamgiamsoc = "hangcu_cat_sanphamgiamsoc";
        $meta_key_sanphamgiamsoc_title = "hangcu_cat_sanphamgiamsoc_title";

        $meta_key_top5 = "hangcu_cat_top5banchay";
        $meta_key_top5_title = "hangcu_cat_top5banchay_title";

        $value_docquyengiatot = get_term_meta( intval($term_id), $meta_key_docquyengiatot, true);
        $value_docquyengiatot_title = get_term_meta( intval($term_id), $meta_key_docquyengiatot_title, true );
        $value_docquyengiatot_title = empty( $value_docquyengiatot_title ) ? __('Độc quyền giá tốt', 'hangcu') : $value_docquyengiatot_title;

        $value_sanphamgiamsoc = get_term_meta( intval($term_id), $meta_key_sanphamgiamsoc, true);
        $value_sanphamgiamsoc_title = get_term_meta( intval($term_id), $meta_key_sanphamgiamsoc_title, true );
        $value_sanphamgiamsoc_title = empty( $value_sanphamgiamsoc_title ) ? __('Sản phẩm giảm sốc', 'hangcu') : $value_sanphamgiamsoc_title;

        $value_top5banchay = get_term_meta( intval($term_id), $meta_key_top5, true );
    ?>
        <tr class="form-field term-slug-wrap">
            <th scope="row">
                <label for="is-cat-select">
                    <?php _e('Độc quyền giá tốt', 'hangcu') ?>
                </label>
            </th>
            <td>
                <p style="margin-bottom: 10px">
                    <?php _e('Tiêu đề:') ?>
                    <input type="text" value="<?php echo $value_docquyengiatot_title; ?>" name="hangcu_cat_docquyengiatot_title" id="hangcu_cat_docquyengiatot_title" />
                </p>
                <p>
                    <?php _e('Danh mục hiển thị:') ?>
                    <select name="hangcu_cat_docquyengiatot" id="hangcu_cat_docquyengiatot" value="<?php echo $value_docquyengiatot ?>">
                        <?php get_categories_select( $term_id, $value_docquyengiatot ); ?>
                    </select>
                </p>
            </td>
        </tr>

        <tr class="form-field term-slug-wrap">
            <th scope="row">
                <label for="is-cat-select">
                    <?php _e('Sản phẩm giảm sốc', 'hangcu') ?>
                </label>
            </th>
            <td>
                <p style="margin-bottom: 10px">
                    <?php _e('Tiêu đề:') ?>
                    <input type="text" value="<?php echo $value_sanphamgiamsoc_title; ?>" name="hangcu_cat_sanphamgiamsoc_title" id="hangcu_cat_sanphamgiamsoc_title" />
                </p>
                <p>
                    <?php _e('Danh mục hiển thị:') ?>
                    <select name="hangcu_cat_sanphamgiamsoc" id="hangcu_cat_sanphamgiamsoc" value="<?php echo $value_sanphamgiamsoc ?>">
                        <?php get_categories_select( $term_id, $value_sanphamgiamsoc ); ?>
                    </select>
                </p>
            </td>
        </tr>

        <tr class="form-field term-slug-wrap">
            <th scope="row">
                <label for="is-cat-select">
                    <?php _e('Top 5 bán chạy', 'hangcu') ?>
                </label>
            </th>
            <td>
                <input type="hidden" name="hangcu_cat_top5banchay" id="hangcu_cat_top5banchay" value='<?php echo $value_top5banchay ?>' />
                <button type="button" id="addnewtop5" class="button"><?php _e("Thêm top bán chạy", 'hangcu') ?></button>
                <div id="addnewcontenttop5"></div>
                <span><?php _e('Danh sách Top bán chạy', 'hangcu') ?></span></br>
                <div id="addnewcontenttop5items" class="drag-container"></div>
                <script>
                    const lstSelectCats = <?php echo json_encode( get_list_categories_select())?>;
                    const $term_id = <?php echo $term_id ?>
                </script>
            </td>
        </tr>
    <?php }
}


if ( !function_exists( 'hangcu_custom_edit_taxonomy' ) ) {
    function hangcu_custom_edit_taxonomy($term_id) {
        $meta_key_docquyengiatot = "hangcu_cat_docquyengiatot";
        $meta_key_sanphamgiamsoc = "hangcu_cat_sanphamgiamsoc";
        $meta_key_top5 = "hangcu_cat_top5banchay";
        $meta_key_docquyengiatot_title = "hangcu_cat_docquyengiatot_title";
        $meta_key_sanphamgiamsoc_title = "hangcu_cat_sanphamgiamsoc_title";

        // độc quyền giá tốt
        $value_docquyengiatot = get_term_meta( $term_id, $meta_key_docquyengiatot);
        if ( !empty( $value_docquyengiatot ) ) {
            update_term_meta( $term_id, $meta_key_docquyengiatot, empty( $_POST['hangcu_cat_docquyengiatot'] ) ? "" : $_POST['hangcu_cat_docquyengiatot'] );
        } else {
            add_term_meta( $term_id, $meta_key_docquyengiatot, empty( $_POST['hangcu_cat_docquyengiatot'] ) ? "" : $_POST['hangcu_cat_docquyengiatot'] );
        }

        $value_docquyengiatot_title = get_term_meta( $term_id, $meta_key_docquyengiatot_title);
        if ( !empty( $value_docquyengiatot ) ) {
            update_term_meta( $term_id, $meta_key_docquyengiatot_title, empty( $_POST['hangcu_cat_docquyengiatot_title'] ) ? "" : $_POST['hangcu_cat_docquyengiatot_title'] );
        } else {
            add_term_meta( $term_id, $meta_key_docquyengiatot_title, empty( $_POST['hangcu_cat_docquyengiatot_title'] ) ? "" : $_POST['hangcu_cat_docquyengiatot_title'] );
        }


        // sản phẩm giảm sốc
        $value_sanphamgiamsoc = get_term_meta( $term_id, $meta_key_sanphamgiamsoc);
        if ( !empty( $value_sanphamgiamsoc ) ) {
            update_term_meta( $term_id, $meta_key_sanphamgiamsoc, empty( $_POST['hangcu_cat_sanphamgiamsoc'] ) ? "" : $_POST['hangcu_cat_sanphamgiamsoc'] );
        } else {
            add_term_meta( $term_id, $meta_key_sanphamgiamsoc, empty( $_POST['hangcu_cat_sanphamgiamsoc'] ) ? "" : $_POST['hangcu_cat_sanphamgiamsoc'] );
        }

        $value_sanphamgiamsoc_title = get_term_meta( $term_id, $meta_key_sanphamgiamsoc_title);
        if ( !empty( $value_docquyengiatot ) ) {
            update_term_meta( $term_id, $meta_key_sanphamgiamsoc_title, empty( $_POST['hangcu_cat_sanphamgiamsoc_title'] ) ? "" : $_POST['hangcu_cat_sanphamgiamsoc_title'] );
        } else {
            add_term_meta( $term_id, $meta_key_sanphamgiamsoc_title, empty( $_POST['hangcu_cat_sanphamgiamsoc_title'] ) ? "" : $_POST['hangcu_cat_sanphamgiamsoc_title'] );
        }

        // top5
        $value_top5 = get_term_meta( $term_id, $meta_key_top5);
        if ( !empty( $value_sanphamgiamsoc ) ) {
            update_term_meta( $term_id, $meta_key_top5, empty( $_POST['hangcu_cat_top5banchay'] ) ? "" : $_POST['hangcu_cat_top5banchay'] );
        } else {
            add_term_meta( $term_id, $meta_key_top5, empty( $_POST['hangcu_cat_top5banchay'] ) ? "" : $_POST['hangcu_cat_top5banchay'] );
        }
    }
}

// return list option category
if ( !function_exists( 'get_list_categories_select' ) ) {
    function get_list_categories_select() {
        $key = "is_cat_select";
        $args = array(
            'hide_empty' => false,
            'meta_query' => array(
                array(
                   'key'       => $key,
                   'value'     => '1',
                   'compare'   => '='
                )
            ),
            'taxonomy'  => 'product_cat',
        );
        $terms = get_terms( $args );
        return $terms;
    }
}
if ( !function_exists('get_categories_select') ) {
    function get_categories_select($term_id, $selected_value = "") {
       
        $terms = get_list_categories_select();
        echo '<option value="">'. __('Chọn 1 danh mục', 'hangcu').'</option>';
        foreach ( $terms as $term ) : 
            if ( $term_id == $term->term_id ) continue;
        ?>
            <option value="<?php echo $term->slug ?>"  <?php echo $term->slug == $selected_value ? "selected" : "" ?> ><?php echo $term->name ?></option>
        <?php endforeach;
    }
}

function add_post_tag_columns($columns){
    $columns['export_data'] = 'Export data';
    return $columns;
}


function category_custom_column_value( $columns, $column, $term_id ) { 
    if ($column == 'export_data') {
        return '<a href="#" id="export_product_cat_'. $term_id .'">Export</a>';
    }
}